<?php
$pageTitle = "Resident About";
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
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto" id="profileNavbar">
                <li class="nav-item active">
                    <a class="nav-link" href="#"> <?php echo 'Welcome, ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> </a>
                </li>
            </ul>
        </div>
    </nav>
    <?php include 'navbar.php'; ?>
    <div class="container" id="welcomeDashboard">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">About</h2>
                    <p class="pJustify">Pateros, officially the Municipality of Pateros; Filipino: Bayan ng Pateros, is the lone municipality of Metro Manila, Philippines. According to the 2020 census, it has a population of 65,227 people. <br>
                        <br>The municipality is famous for its duck-raising industry and especially for producing balut, a Filipino delicacy, which is a boiled, fertilized duck egg. Pateros is also known for the production of red salty eggs and "inutak", a local rice cake. Moreover, the town is known for manufacturing of "alfombra", a locally-made footwear with a carpet-like fabric on its top surface. Pateros is bordered by the highly urbanized cities of Pasig to the north, and by Taguig to the east, west and south. <br>
                        <br>Pateros is the smallest municipality both in population and in land area, in Metro Manila, but it is the second most densely populated at around 37,000 inhabitants per square kilometer or 96,000 inhabitants per square mile after the capital city of Manila. Unlike its neighbors in Metro Manila, Pateros is the only municipality in the region.
                    </p>
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">Mission</h2>
                    <p class="pJustify">The next project which we are already starting now will be a powerful Ebike Solar Charging Stations for Ebike Users in Pateros. With this project, we can help Etricycle users to lower their electric charging bill possibly by half or even none at all if they will allow us to setup their own Ebike Solar Charging Station in their homes. <br>
                        <br>It is also to serve the public with excellence and integrity in all of our efforts.
                    </p>
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">Vision</h2>
                    <p class="pJustify">To become a brownout free town and some people already know that it is now the first Solar City in the Philippines for its advancement in solar projects and for its transformation as a Green City with Good Renewable Energy Technologies. <br>
                        <br>It is also to build a well-organized and thriving community, ensuring a better future for all.
                    </p>
                </div>
            </div>
            <div class="col-md-12 mb-5">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">Goals</h2>
                    <p class="pJustify">Our goals include enhancing infrastructure, supporting education, and ensuring the welfare of every citizen.</p>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php include 'footer.php'; ?>
</body>