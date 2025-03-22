<div class="menu-bar" id="menuBar">
    <ul>
        <li><a href="resident_home.php" id="nagivatelink">Home</a></li>
        <li><a href="resident_about.php" id="nagivatelink">About</a></li>
        <li><a href="resident_profile.php" id="nagivatelink">Profile</a></li>
        <li><a href="resident_ticket.php" id="nagivatelink">Ticket</a></li>
        <li><a href="../index.php" id="nagivatelink">
        <?php if (isset($_SESSION['user_id'])): ?> Logout <?php endif; ?>
        </a></li>
    </ul>
</div>
<link rel="stylesheet" href="navbar2.css">
<script src="navbar.js"></script>
