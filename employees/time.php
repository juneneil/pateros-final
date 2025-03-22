<?php
$pageTitle = "Employee Time Logs";
$WithoutEmployeeCSS = True;
include 'sessions.php';
include 'header.php';
?>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="navBar">
        <a class="navbar-brand" href="employee_home.php">
        <img src="images/PaterosLogo.png" alt="Pateros Logo"> Pateros Municipality </a>
        <a href="#" class="sidebar-toggle" onclick="toggleSidebar()">
        <span class="sr-only">Toggle navigation</span> &#9776; </a>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto" id="profileNavbar">
            <li class="nav-item active">
            <a class="nav-link" href="#"><?php echo 'Welcome, ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];?> </a>
            </li>
        </ul>
        </div>
    </nav>
    <?php include 'navbar.php'; ?>
    <center>
        <div class="container" id="welcomeDashboard">
        <div class="row">
            <div class="col-md-12 mb-4">
            <div class="section-box p-4 bg-light rounded shadow"> <?php include("time_index.php"); ?> </div>
            </div>
        </div>
    </center>
    <?php include 'footer.php'; ?>
</body>