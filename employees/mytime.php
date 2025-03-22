<?php
$pageTitle = "Employee Time Records";
$WithoutEmployeeCSS = true;
$WithEmployeeCSS = false;
include 'sessions.php';
include "../conn.php";
// $user_id = $_SESSION["employee_id"];
$user_id = $_SESSION["user_id"];

// Get the current page number from URL or set to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5; // Number of tickets per page
$offset = ($page - 1) * $perPage;

// Get total ticket count for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM attendance WHERE employee_id = ?";
$totalStmt = $conn->prepare($totalQuery);
// $totalStmt->bind_param('s', $_SESSION['employee_id']);
$totalStmt->bind_param('s', $user_id);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalTickets = $totalRow['total'];
$totalPages = ceil($totalTickets / $perPage);

// Query to fetch tickets for the current page
$fetch_tickets_query = "SELECT time_in, time_out, num_hr, status, 
date
FROM attendance WHERE employee_id = ? LIMIT ?, ?";
$stmt = $conn->prepare($fetch_tickets_query);
// $stmt->bind_param('sii', $_SESSION['employee_id'], $offset, $perPage);
$stmt->bind_param('sii', $user_id, $offset, $perPage);
$stmt->execute();
$result = $stmt->get_result();

include 'header.php';
?>
<body>
    <script>
        var employeeIdFromForm = $('#employee').val();
        var loggedInEmployeeId = '<?php echo $_SESSION['employee_id']; ?>';
    </script>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id = "navBar">
        <a class="navbar-brand" href="employee_home.php">
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
    <div class="col-md-12 mb-4">
        <div class="section-box p-4 bg-light rounded shadow">
            <div class="container mt-5">
                <h3>Time Records</h3>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered text-white" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Number of hours</th>
                                <th>Status</th>
                                <th>Date</th>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                    <td>{$row['time_in']}</td>
                                    <td>{$row['time_out']}</td>
                                    <td>{$row['num_hr']}</td>
                                    <td>{$row['status']}</td>
                                    <td>{$row['date']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No tickets found for this employee.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Controls -->
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