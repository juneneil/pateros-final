<?php
session_start();
include "../conn.php";

$pageTitle = "Create Resident";
$WithEmployeeCSS = false;

// Firebase Credentials
define("FIREBASE_API_KEY", "AIzaSyDXuS34OIspDbMFtYuU-DnRhwb3ilLNHts");
define("FIREBASE_AUTH_URL", "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=" . FIREBASE_API_KEY);
define("FIREBASE_DATABASE_URL", "https://web-attendance-system-c1214-default-rtdb.firebaseio.com/residents.json");
define("FIREBASE_STORAGE_BUCKET", "web-attendance-system-c1214.appspot.com");

// Handle form submission
if (isset($_POST["register"])) {
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $address = trim($_POST["address"]);
    $birthdate = $_POST["birthdate"];
    $contact = trim($_POST["contact_info"]);
    $gender = trim($_POST["gender"]);
    $age = trim($_POST["age"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    // Generate Resident ID
    $resident_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3) . random_int(100000000, 999999999);

    // Validate required fields
    if (empty($email) || empty($password) || empty($firstname) || empty($lastname) || empty($age) || empty($birthdate) || empty($gender) || empty($contact)) {
        $_SESSION["error"] = "All fields are required!";
        header("Location: register_residents.php");
        exit();
    }

    // Handle Image Upload (Firebase Storage)
    $photo_url = "";
    $filename = "";
    if (!empty($_FILES["photo"]["name"])) {
        $temp = $_FILES["photo"]["tmp_name"];
        $filename = basename($_FILES["photo"]["name"]);
        $firebase_storage_path = "resident_photos/" . time() . "_" . $filename;
        
        // Move file temporarily to server before uploading to Firebase Storage
        $local_path = "../images/" . $filename;
        move_uploaded_file($temp, $local_path);

        // Upload to Firebase Storage using cURL
        $storage_url = "https://firebasestorage.googleapis.com/upload/storage/v1/b/" . FIREBASE_STORAGE_BUCKET . "/o?uploadType=media&name=" . urlencode($firebase_storage_path);
        $photo_url = "https://firebasestorage.googleapis.com/v0/b/" . FIREBASE_STORAGE_BUCKET . "/o/" . urlencode($firebase_storage_path) . "?alt=media";
        
        $ch = curl_init($storage_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . FIREBASE_API_KEY,
            "Content-Type: application/octet-stream"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($local_path));
        curl_exec($ch);
        curl_close($ch);
    }

    // Create user in Firebase Authentication
    $auth_data = json_encode([
        "email" => $email,
        "password" => $password,
        "returnSecureToken" => true
    ]);

    $ch = curl_init(FIREBASE_AUTH_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_data);
    $auth_response = curl_exec($ch);
    curl_close($ch);

    $auth_result = json_decode($auth_response, true);

    if (isset($auth_result["idToken"])) {
        $firebase_uid = $auth_result["localId"];

        // Save resident data to Firebase Realtime Database
        $user_data = json_encode([
            "resident_id" => $resident_id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "address" => $address,
            "birthdate" => $birthdate,
            "contact_info" => $contact,
            "gender" => $gender,
            "age" => $age,
            "photo" => $photo_url,
            "email" => $email,
            "firebase_uid" => $firebase_uid
        ]);

        $db_ch = curl_init(FIREBASE_DATABASE_URL);
        curl_setopt($db_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($db_ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($db_ch, CURLOPT_POST, true);
        curl_setopt($db_ch, CURLOPT_POSTFIELDS, $user_data);
        curl_exec($db_ch);
        curl_close($db_ch);

        // Insert into MySQL Database
        $sql = "INSERT INTO residents (resident_id, firstname, lastname, address, birthdate, contact_info, gender, age, photo, created_on, email, password) 
                VALUES ('$resident_id', '$firstname', '$lastname', '$address', '$birthdate', '$contact', '$gender', '$age', '$filename', NOW(), '$email', '$password')";
        
        if ($conn->query($sql)) {
            $_SESSION["success"] = "Resident added successfully! Verification email sent.";
        } else {
            $_SESSION["error"] = "MySQL Error: " . $conn->error;
        }

// Send email verification link
        $verify_url = "https://identitytoolkit.googleapis.com/v1/accounts:sendOobCode?key=" . FIREBASE_API_KEY;
        $verify_data = json_encode([
            "requestType" => "VERIFY_EMAIL",
            "idToken" => $auth_result["idToken"]
        ]);

        $verify_ch = curl_init($verify_url);
        curl_setopt($verify_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($verify_ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($verify_ch, CURLOPT_POST, true);
        curl_setopt($verify_ch, CURLOPT_POSTFIELDS, $verify_data);
        curl_exec($verify_ch);
        curl_close($verify_ch);

        $_SESSION["success"] = "Resident added successfully! Verification email sent.";
    } else {
        $_SESSION["error"] = "Firebase Error: " . json_encode($auth_result["error"]);
    }
    
    header("Location: register_residents.php");
    exit();
}

include 'header.php';
?>

<style>
body {
    padding-top: 0;
    background-image: url("images/PaterosMunicipal.jpg");
    background-repeat: no-repeat;
    background-size: cover; /* Ensures the image covers the entire screen */
    background-position: center; /* Centers the image */
    background-attachment: fixed; /* Keeps the background fixed when scrolling */
    min-height: 100vh;
    overflow-x: hidden;
    overflow-y: auto;
    font-family: Arial, sans-serif;
}

p {
    font-size: 14px;
}

#register_style {
    font-size: x-small;
}

input {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 12px;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
    color: #555;

    margin-top: 10px;
}

input[type="text"], input[type="password"], input[type="email"], input[type="date"], input[type="file"] {
    width: 50%;
    padding: 10px;
    margin-bottom: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
    text-align: center;
    border-color: #45a049;
}

input[type="file"] {
    margin-bottom: 5px;
}

input[type="submit"] {
    width: 50%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 auto;
}

.image img {
    width: 100%;
    max-width: 600px;
    height: auto;
}

.login-container {
    width: 100%;
    max-width: 600px;
    padding: 20px;
    box-sizing: border-box;
    margin-top: 20px;
    background-color: #f9f9f9;
    border-radius: 5px;

    background-image: 'images/PaterosMunicipal.jpg';
}

@media (max-width: 768px) {
    input[type="text"], input[type="password"], input[type="email"], input[type="date"], input[type="file"] {
        width: 80%;
    }
}

@media (max-width: 480px) {
    input[type="text"], input[type="password"], input[type="email"], input[type="date"], input[type="file"] {
        width: 100%;
    }

    .login-container {
        padding: 15px;
    }

    .image img {
        width: 90%;
    }

    input[type="submit"] {
        width: 100%;
    }
}

.p-login-margin {
  margin-bottom: 0;
}

/* FOR ALERT CSS */
div.alert.alert-success {
    padding: 5px 10px 5px 10px;
    background-color: green;
    color: white;
    font-size: 20px;
}

#alert-success {
    display: flex;
    justify-content: center;
    align-items: center;
}


/* END FOR ALERT CSS */

</style>

<body>
    <div class="container">
        <div class="login-container" id="register_style">
            <center>
            <h1>Create Resident Account</h1>
            <p>Fill in the details to create a new user account for resident.</p>
            </center>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div id="alert-success">
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <center>
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>

                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>

                    <label for="age">Age</label>
                    <input type="text" id="age" name="age" placeholder="Enter your age" required>

                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" placeholder="Enter your birthdate" required>

                    <label for="gender">Gender</label>
                    <input type="text" id="gender" name="gender" placeholder="Enter your Gender" required>

                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Enter your Address" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>

                    <label for="contact">Contact Number</label>
                    <input type="text" id="contact" name="contact_info" placeholder="Enter your phone number" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>

                    <label for="photo">Upload Photo</label>
                    <div>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    </div>
                    <input type="submit" name="register" value="Create Account">
                </center>
            </form>
            <center>
                <p class="p-login-margin">Do you want to go back?</p>
                <a href="../index.php"><input type="submit" value="Back to Login" class="btn btn-primary"></a>
            </center>
        </div>
    </div>
</body>
