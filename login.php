<?php
session_start();
session_unset();
session_destroy();
session_start();
include "conn.php";

define("FIREBASE_API_KEY", "AIzaSyDXuS34OIspDbMFtYuU-DnRhwb3ilLNHts");
define("FIREBASE_SIGNIN_URL", "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=" . FIREBASE_API_KEY);
define("FIREBASE_GET_USER_URL", "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=" . FIREBASE_API_KEY);

$pageTitle = "Login - Pateros Municipality";
$WithEmployeeCSS = true;

if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        $login_data = json_encode([
            "email" => $email,
            "password" => $password,
            "returnSecureToken" => true
        ]);
        $ch = curl_init(FIREBASE_SIGNIN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data);
        $firebase_response = curl_exec($ch);
        curl_close($ch);
        $auth_result = json_decode($firebase_response, true);

        if (isset($auth_result["idToken"])) {
            $get_user_data = json_encode(["idToken" => $auth_result["idToken"]]);
            $ch = curl_init(FIREBASE_GET_USER_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $get_user_data);
            $user_response = curl_exec($ch);
            curl_close($ch);
            $user_info = json_decode($user_response, true);

            if (isset($user_info["users"][0]["emailVerified"]) || $user_info["users"][0]["emailVerified"]) {
                $sql = "SELECT * FROM residents WHERE email = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if ($password == $user["password"]) {
                        $_SESSION["user_id"] = $user["id"];
                        $_SESSION["resident_id"] = $user["resident_id"];
                        $_SESSION["email"] = $user["email"];
                        $_SESSION["firstname"] = $user["firstname"];
                        $_SESSION["lastname"] = $user["lastname"];
                        $_SESSION["success"] = "Login successful!";

                        header("Location: residents/resident_home.php");
                        exit();
                    } else {
                        $_SESSION["error"] = "Invalid password";
                    }
                } else {
                    $_SESSION["error"] = "User not found in MySQL Database.";
                }
            } else {
                $_SESSION["error"] = "Please verify your email before logging in. Check your inbox.";
            }
        } else {
            $_SESSION["error"] = "Invalid email or password.";
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
            <style>
                .back-btn {
                display: inline-block;
                padding: 0.8em 1.5em;
                background-color: rgb(30, 144, 255);
                color: white;
                text-decoration: none;
                font-weight: 600;
                font-size: 1rem;
                border-radius: 0.5em;
                transition: all 0.3s ease;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                max-width: 20%;
                text-align: center;
                white-space: nowrap;
                }

                .back-btn:hover {
                background-color: rgb(0, 123, 255);
                transform: translateY(-2px);
                }

                @media (max-width: 600px) {
                .back-btn {
                    display: block;
                    margin: 1em auto;
                    width: 80%;
                    font-size: 1.1rem;
                }
                }
            </style>
        <a href="index.php" class="back-btn">← Back</a>
            <center>
            <h1>Residents Login</h1>
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
            <form action="login.php" method="post">
                <center>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <input type="submit" name="login" value="Login">
                </center>
            </form>
            <br>
            <br>
            <br>
            <center>
                <p class="p-login-margin">Are you an employee?</p>
                <form action="employees/index.php" method="get">
                    <input type="submit" value="Employee Login" class="btn btn-primary">
                </form>
            </center>
            <br>
            <center>
                <p class="p-login-margin">Create Resident Account</p>
                <form action="residents/register_residents.php" method="get">
                    <input type="submit" value="Create Now" class="btn btn-primary">
                </form>
            </center>
        </div>
    </div>
</body>
