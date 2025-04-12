<?php
$pageTitle = "Resident Ticket";
$WithoutEmployeeCSS = true;
$WithEmployeeCSS = false;
include 'sessions.php';
include "../conn.php";

// Firebase Credentials
define("FIREBASE_API_KEY", "AIzaSyDXuS34OIspDbMFtYuU-DnRhwb3ilLNHts");
define("FIREBASE_AUTH_URL", "https://identitytoolkit.googleapis.com/v1/accounts:sendOobCode?key=" . FIREBASE_API_KEY);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_id = trim($_POST["id"]);
    $resident_id = trim($_POST["resident_id"]);
    $category = trim($_POST["category"]);
    $sub_category = trim($_POST["sub_category"]);
    // $booking_date = trim($_POST["booking_date"]);
    $booking_date = date('Y-m-d H:i:s', strtotime($_POST["booking_date"]));
    $reason_for_inquiry = trim($_POST["reason_for_inquiry"]);
    $remarks = isset($_POST["remarks"]) && !empty($_POST["remarks"]) ? trim($_POST["remarks"]) : "Not Serve";
    $approval = isset($_POST["approval"]) && !empty($_POST["approval"]) ? trim($_POST["approval"]) : "Not Approved";

    // Insert the ticket data into the database
    $insert_query = "INSERT INTO ticket (id, resident_id, category, sub_category, booking_date, reason_for_inquiry, remarks, approval) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    // $insert_stmt->bind_param("isssss", $user_id, $resident_id, $category, $sub_category, $booking_date, $reason_for_inquiry);
    $insert_stmt->bind_param("isssssss", $user_id, $resident_id, $category, $sub_category, $booking_date, $reason_for_inquiry, $remarks, $approval);

    if ($insert_stmt->execute()) {
        // Fetch the resident's email
        $email_query = "SELECT email FROM residents WHERE resident_id = ?";
        $email_stmt = $conn->prepare($email_query);
        $email_stmt->bind_param("s", $resident_id);
        $email_stmt->execute();
        $email_result = $email_stmt->get_result();

        if ($email_result->num_rows > 0) {
            $row = $email_result->fetch_assoc();
            $resident_email = $row['email'];

            $firebase_data = json_encode([
                "requestType" => "PASSWORD_RESET",
                "email" => $resident_email
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, FIREBASE_AUTH_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $firebase_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                echo "<script>alert('Ticket created successfully! A confirmation link has been sent to $resident_email');</script>";
            } else {
                echo "<script>alert('Ticket created successfully! However, the confirmation link could not be sent.');</script>";
            }
           } else {
               echo "<script>alert('Ticket created successfully! No email found for the given resident ID.');</script>";
           }
   
           $email_stmt->close();
   } else {
       echo "<script>alert('Error: " . $conn->error . "');</script>";
   }
    $insert_stmt->close();
    echo "<script>window.location.href='resident_ticket.php';</script>";
}

$user_id = $_SESSION["resident_id"];

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5; 
$offset = ($page - 1) * $perPage;

$totalQuery = "SELECT COUNT(*) AS total FROM ticket WHERE resident_id = ?";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param('s', $_SESSION['resident_id']);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalTickets = $totalRow['total'];
$totalPages = ceil($totalTickets / $perPage);

$fetch_tickets_query = "SELECT id, category, sub_category, booking_date,  reason_for_inquiry, remarks, approval 
                        FROM ticket WHERE resident_id = ? LIMIT ?, ?";
$stmt = $conn->prepare($fetch_tickets_query);
$stmt->bind_param('sii', $_SESSION['resident_id'], $offset, $perPage);
$stmt->execute();
$result = $stmt->get_result();

include 'header.php';
?>

<body>
    <script>
        var residentIdFromForm = $('#resident').val();
        var loggedInresidentId = '<?php echo $_SESSION['resident_id']; ?>';

        // Change sub-category options based on category selection
        $(document).ready(function() {
            // Hide all sub-category divs initially and disable all sub-category radios
            $(".sub-category-div").hide();
            $(".sub-category-radio").prop("disabled", true);

            $("#category").change(function() {
                var category = $(this).val();
                $(".sub-category-div").hide(); // Hide all sub-category divs
                $(".sub-category-radio").prop("disabled", true); // Disable all radio buttons
                $("#business_permit").hide();

                // Enable and show the sub-categories for the selected category
                if (category == "Civil Registry") {
                    $("#civil_registry_div").show();
                    $("#civil_registry_radio").prop("disabled", false);
                    $("#business_registration_div").hide();
                    $("#job_opportunities_div").hide();
                    $("#dswd_div").hide();
                } else if (category == "Business Registration") {
                    $("#business_registration_div").show();
                    $("#business_registration_radio").prop("disabled", false);
                    $("#civil_registry_div").hide();
                    $("#job_opportunities_div").hide();
                    $("#dswd_div").hide();
                } else if (category == "Job Opportunities") {
                    $("#job_opportunities_div").show();
                    $("#job_opportunities_radio").prop("disabled", false);
                    $("#civil_registry_div").hide();
                    $("#business_registration_div").hide();
                    $("#dswd_div").hide();
                } else if (category == "DSWD") {
                    $("#dswd_div").show();
                    $("#dswd_radio").prop("disabled", false);
                    $("#civil_registry_div").hide();
                    $("#business_registration_div").hide();
                    $("#job_opportunities_div").hide();
                }

            });
        });
    </script>

    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="navBar">
        <a class="navbar-brand" href="resident_home.php">
            <img src="images/PaterosLogo.png" alt="Pateros Logo">
            Pateros Municipality
        </a>
        <a href="#" class="sidebar-toggle" onclick="toggleSidebar()">
            <span class="sr-only">Toggle navigation</span>
            &#9776;
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto" id="profileNavbar">
                <li class="nav-item active">
                    <a class="nav-link" href="#">
                        <?php echo "Welcome, " . $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <?php include 'navbar.php'; ?>

    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div style="display: flex; justify-content: center; align-items: center;">
    <button class="btn btn-primary ml-3" data-toggle="modal" data-target="#ticketModal">Add New Ticket</button>
</div>

<div class="modal" id="ticketModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Ticket</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <?php echo $_SESSION["resident_id"]; ?>
                        </div>
                    </div>
                    <div class="form-group text-dark">
                        <label for="resident_id"><b>Resident ID:</b></label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION["resident_id"]; ?>" id="resident_id" name="resident_id" required>
                    </div>
                    <div class="form-group text-dark">
                        <h5>Please choose your category out of the 4 main categories.</h5>
                        <label for="category"><b>Category:</b></label><br>

                        <input type="radio" id="civil_registry" name="category" value="Civil Registry" required>
                        <label for="civil_registry">Civil Registry</label><br>

                        <input type="radio" id="business_registration" name="category" value="Business Registration" required>
                        <label for="business_registration">Business Registration</label><br>

                        <input type="radio" id="job_opportunities" name="category" value="Job Opportunities" required>
                        <label for="job_opportunities">Job Opportunities</label><br>

                        <input type="radio" id="dswd" name="category" value="DSWD" required>
                        <label for="dswd">DSWD</label><br>
                    </div>
                    <div class="form-group text-dark sub-category-div" id="civil_registry_div">
                    <h5>Please choose the sub-category from the main category that you've chosen above.</h5>
                        <label for="sub_category"><b>Civil Registry:</b></label>
                        <br>
                        <input type="radio" id="birth_service" class="sub-category-radio" name="sub_category" value="Birth Service" required>
                        <label for="birth_service">Birth Service</label>
                        <br>
                        <input type="radio" id="death_service" class="sub-category-radio" name="sub_category" value="Death Service" required>
                        <label for="death_service">Death Service</label>
                        <br>
                        <input type="radio" id="marriage_service" class="sub-category-radio" name="sub_category" value="Marriage Service" required>
                        <label for="marriage_service">Marriage Service</label>
                        <br>
                        <input type="radio" id="cedula" class="sub-category-radio" name="sub_category" value="Cedula" required>
                        <label for="cedula">Cedula</label>
                        <br>
                        <input type="radio" id="voters_certificate" class="sub-category-radio" name="sub_category" value="Voters Certificate" required><label for="voters_certificate">Voters Certificate</label><br>
                    </div>
                    <div class="form-group text-dark sub-category-div" id="business_registration_div">
                        <label for="sub_category"><b>Business Registration:</b></label>
                        <br>
                        <input type="radio" id="business_permit" class="sub-category-radio" name="sub_category" value="Business Permit" required>
                        <label for="business_permit">Business Permit</label>
                        <br>
                        <input type="radio" id="business_renewal" class="sub-category-radio" name="sub_category" value="Business Renewal" required>
                        <label for="business_renewal">Business Renewal</label>
                        <br>
                    </div>
                    <div class="form-group text-dark sub-category-div" id="job_opportunities_div">
                        <label for="sub_category"><b>Job Opportunities:</b></label><br>
                        <input type="radio" id="peso_jobs" class="sub-category-radio" name="sub_category" value="Peso Jobs" required><label for="peso_jobs">Peso Jobs</label><br>
                        <input type="radio" id="national_jobs" class="sub-category-radio" name="sub_category" value="National Jobs" required><label for="national_jobs">National Jobs</label><br>
                        <input type="radio" id="tupad" class="sub-category-radio" name="sub_category" value="Tupad" required><label for="tupad">Tupad</label><br>
                    </div>
                    <div class="form-group text-dark sub-category-div" id="dswd_div">
                        <label for="sub_category"><b>DSWD:</b></label><br>
                        <input type="radio" id="family_planning" class="sub-category-radio" name="sub_category" value="Family Planning" required><label for="family_planning">Family Planning</label><br>
                        <input type="radio" id="feeding_program" class="sub-category-radio" name="sub_category" value="Feeding Program" required><label for="feeding_program">Feeding Program</label><br>
                        <input type="radio" id="4ps" class="sub-category-radio" name="sub_category" value="4PS" required><label for="4ps">4PS</label><br>
                    </div>
                    <div class="form-group text-dark">
                        <label for="booking_date"><b>Booking Date & Time:</b></label><br>
                        <input type="datetime-local" id="booking_date" name="booking_date" required>
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            let bookingInput = document.getElementById("booking_date");
                            let now = new Date();
                            
                            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                            bookingInput.min = now.toISOString().slice(0, 16);
                            bookingInput.addEventListener("change", function () {
                                let inputDate = new Date(this.value);
                                let currentDate = new Date();
                                let hours = inputDate.getHours();
                                let minutes = inputDate.getMinutes();

                                if (inputDate < currentDate) {
                                    alert("Error: You cannot select a past date.");
                                    this.value = "";
                                } else if (hours < 7 || (hours === 16 && minutes > 0) || hours > 16) {
                                    alert("Error: Booking time must be between 7:00 AM and 4:00 PM.");
                                    this.value = "";
                                }
                            });
                        });
                    </script>
                    <div class="form-group text-dark">
                        <label for="reason_for_inquiry">Reason for Inquiry:</label>
                        <textarea class="form-control" id="reason_for_inquiry" name="reason_for_inquiry" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <div class="col-md-12 mb-4">
        <div class="section-box p-4 bg-light rounded shadow">
            <div class="container mt-5">
                <h3>Your Tickets</h3>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered text-white" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Booking Date</th>
                                <th>Reason for Inquiry</th>
                                <th>Remarks</th>
                                <th>Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['sub_category']}</td>
                                    <td>{$row['booking_date']}</td>
                                    <td>{$row['reason_for_inquiry']}</td>
                                    <td>{$row['remarks']}</td>
                                    <td>{$row['approval']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No tickets found for this resident.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#">Previous</a>
                            </li>
                        <?php } ?>
                        <li class="page-item disabled">
                            <span class="page-link">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                        </li>
                        <?php if ($page < $totalPages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

<?php
$stmt->close();
$totalStmt->close();
?>
