<?php
$pageTitle = "Resident Profile";
$WithoutEmployeeCSS = True;
include 'sessions.php';
include "../admin/includes/conn.php";
$user_id = $_SESSION["user_id"];
$query ="SELECT contact_info, address, gender, created_on FROM residents WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->bind_result(
        $contact_info,
        $address,
        $gender,
        $created_on
    );
    $stmt->fetch();
} else {
    $contact_info = "Contact info not available.";
    $address = "Address not available.";
    $gender = "Gender not available.";
    $created_on = "Account creation date not available.";
}
$stmt->close();
include 'header.php';
?>
<body>
<style>
.flex-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.section-box {
    width: 100%;
}
@media (min-width: 768px) {
    .section-box {
        width: 48%;
    }
}
@media (max-width: 767px) {
    .section-box {
        width: 100%;
    }
}
</style>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="navBar">
        <a class="navbar-brand" href="resident_home.php">
        <img src="images/PaterosLogo.png" alt="Pateros Logo"> Pateros Municipality </a>
        <a href="#" class="sidebar-toggle" onclick="toggleSidebar()">
        <span class="sr-only">Toggle navigation</span> &#9776; </a>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto" id="profileNavbar">
            <li class="nav-item active">
            <a class="nav-link" href="#"> <?php echo 'Welcome, ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> </a>
            </li>
        </ul>
        </div>
    </nav>
    <?php include 'navbar.php'; ?>
    <div class="container mycontainer" id="welcomeDashboard">
    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <div class="section-box p-4 bg-light rounded shadow h-100">
                <h5 class="section-title">Full Name</h5>
                <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="section-box p-4 bg-light rounded shadow h-100">
                <h5 class="section-title">Email Address</h5>
                <div class="text-break">
                    <?php echo $_SESSION['email']; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="section-box p-4 bg-light rounded shadow h-100">
                <h5 class="section-title">Phone Number</h5>
                <?php echo $contact_info; ?>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="section-box p-4 bg-light rounded shadow h-100">
                <h5 class="section-title">Location Address</h5>
                <?php echo $address; ?>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="section-box p-4 bg-light rounded shadow h-100">
                <h5 class="section-title">Gender</h5>
                <?php echo $gender; ?>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="section-box p-4 bg-light rounded shadow h-100">
                <h5 class="section-title">Account Created</h5>
                <?php echo $created_on; ?>
            </div>
        </div>
    </div>
</div>

    <br>
    <?php include 'footer.php'; ?>
</body>
