<link rel="stylesheet" href="navbar.css">

<nav class="navbar navbar-expand-lg navbar-light bg-light" id="navBar"> <!-- navbar-light -->
    <a class="navbar-brand" href="employee_home.php">
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

<script src="navbar.js"></script>