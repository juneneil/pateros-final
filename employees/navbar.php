<div class="menu-bar" id="menuBar">
    <ul>
        <li><a href="employee_home.php" id="nagivatelink">Home</a></li>
        <li><a href="employee_about.php" id="nagivatelink">About</a></li>
        <li><a href="time.php" id="nagivatelink">Time-in/Out</a></li>
        <li><a href="mytime.php" id="nagivatelink">My Time</a></li>
        <li><a href="employee_profile.php" id="nagivatelink">Profile</a></li>
        <li><a href="../index.php" id="nagivatelink">
        <?php if (isset($_SESSION['user_id'])): ?> Logout <?php endif; ?>
        </a></li>
    </ul>
</div>
<link rel="stylesheet" href="navbar.css">
<script src="navbar.js"></script>
