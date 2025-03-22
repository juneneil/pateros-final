<?php
session_start();
session_unset();
session_destroy();
session_start();
include "../conn.php";
$pageTitle = "Employee Login";
$WithEmployeeCSS = True;
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM employees WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password == $user["password"]) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["employee_id"] = $user["employee_id"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["firstname"] = $user["firstname"];
                $_SESSION["lastname"] = $user["lastname"];
                $_SESSION["position"] = $user["position"];
                $_SESSION["success"] = "Login successful!";
                if ($user["password"] == $user["lastname"]) {
                    header("Location: employee_change_password.php");
                    exit();
                } else {
                    header("Location: employee_home.php");
                    exit();
                }
            } else {
                $_SESSION["error"] = "Invalid password";
            }
        } else {
            $_SESSION["error"] = "User not found";
        }
    } else {
        $_SESSION["error"] = "Please fill in both fields";
    }
}
include 'header.php';
?>
<body>
    <div class="container">
        <div class="image">
            <img src="images/PaterosMunicipal.jpg" alt="Pateros Building">
        </div>
        <div class="login-container">
            <center>
            <h1>Employees Login</h1>
            <p>Login with the data that admin provided you.</p>
            </center>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form action="index.php" method="post">
                <center>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <input type="submit" name="login" value="Login">
                </center>
            </form>
            <center>
                <p class="p-login-margin">Do you want to go back?</p>
                <a href="../index.php"><input type="submit" value="Back to Login" class="btn btn-primary"></a>
            </center>
        </div>
    </div>
</body>
