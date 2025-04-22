<?php
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
    $photo_type = trim($_POST["photo_type"]);
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

    $filename = "";
    if (!empty($_FILES["photo"]["name"])) {
        $temp = $_FILES["photo"]["tmp_name"];
        $filename = basename($_FILES["photo"]["name"]);

        $local_path = "../images/" . $filename;
        move_uploaded_file($temp, $local_path);
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

        $selfie_filename = '';
        if (!empty($_POST['selfie_with_id'])) {
            $base64_image = $_POST['selfie_with_id'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                $type = strtolower($type[1]);
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    $_SESSION["error"] = "Only JPG, JPEG, or PNG images are allowed.";
                    header("Location: register_residents.php");
                    exit();
                }
                $base64_image = base64_decode($base64_image);
                if ($base64_image === false) {
                    $_SESSION["error"] = "Base64 decode failed.";
                    header("Location: register_residents.php");
                    exit();
                }
                $selfie_filename = 'selfie_' . uniqid() . '.' . $type;
                $file_path = '../images/' . $selfie_filename;
                file_put_contents($file_path, $base64_image);
            }
        }

        // Insert into MySQL Database
        $sql = "INSERT INTO residents (resident_id, firstname, lastname, address, birthdate, contact_info, gender, photo_type, age, photo, created_on, email, password, selfie_with_id) 
                VALUES ('$resident_id', '$firstname', '$lastname', '$address', '$birthdate', '$contact', '$gender', '$photo_type', '$age', '$filename', NOW(), '$email', '$password', '$selfie_filename')";

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
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
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

    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="date"],
    input[type="file"] {
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

    .reg {
        width: 50%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .reg:hover {
        background-color: #45a049;
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

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        input[type="file"] {
            width: 80%;
        }
    }

    @media (max-width: 480px) {

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        input[type="file"] {
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

    #birthdate {
        background-color: #f0f0f0;
        color: #333;
    }

    select#photo_type {
        width: 100%;
        max-width: 280px;
        padding: 12px 16px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        color: #333;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    select#photo_type:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        background-color: #fff;
    }
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
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div id="alert-success">
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
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

                    <label for="photo_type">Select Type of ID</label>
                    <select id="photo_type" name="photo_type" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="Passport">Passport</option>
                        <option value="License">License</option>
                        <option value="PhilID">PhilID</option>
                        <option value="Sr. Citizen ID">Sr. Citizen ID</option>
                        <option value="BRGY ID">BRGY ID</option>
                        <option value="GSIS">GSIS</option>
                        <option value="PRC ID">PRC ID</option>
                        <option value="Voter’s ID">Voter’s ID</option>
                        <option value="Postal ID">Postal ID</option>
                        <option value="PWD ID">PWD ID</option>
                        <option value="SSS">SSS</option>
                    </select>

                    <label onclick="openCamera()" class="photo-btn">📸 Take a photo of yourself holding your ID</label>
                    <style>
                    .photo-btn {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: white;
                        font-weight: 500;
                        border-radius: 6px;
                        cursor: pointer;
                        transition: background-color 0.3s, transform 0.2s;
                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                        user-select: none;
                        text-align: center;
                    }
                    .photo-btn:hover {
                        background-color: #0056b3;
                        transform: scale(1.02);
                    }
                    .media-wrapper {
                        width: 100%;
                        max-width: 540px;
                        margin-top: 10px;
                    }
                    video, canvas {
                        width: 100%;
                        height: auto;
                        border: 2px solid #ccc;
                        border-radius: 8px;
                        display: block;
                    }
                    button {
                        margin-top: 10px;
                        margin-right: 10px;
                        padding: 8px 16px;
                        border-radius: 6px;
                        border: none;
                        background-color: #007bff;
                        color: white;
                        cursor: pointer;
                    }
                    button:hover {
                        background-color: #0056b3;
                    }
                    @media only screen and (max-width: 600px) {
                        .media-wrapper {
                            max-width: 100%;
                        }
                    }
                    </style>
                    <div id="cameraSection" style="display: none;">
                        <div class="media-wrapper">
                            <video id="camera" autoplay playsinline></video>
                            <canvas id="snapshot" style="display: none;"></canvas>
                        </div>
                        <button type="button" onclick="takeSnapshot()" id="captureBtn">📷 Capture</button>
                        <button type="button" onclick="retakePhoto()" id="retakeBtn" style="display: none;">🔁 Retake</button>
                        <input type="hidden" name="selfie_with_id" id="photoData">
                    </div>
                    <script>
                    let videoStream;
                    function openCamera() {
                        document.getElementById('cameraSection').style.display = 'block';
                        navigator.mediaDevices.getUserMedia({
                            video: { width: { ideal: 1280 }, height: { ideal: 720 } }
                        })
                        .then(function(stream) {
                            videoStream = stream;
                            const video = document.getElementById('camera');
                            video.srcObject = stream;
                            video.onloadedmetadata = () => video.play();
                        })
                        .catch(function(err) {
                            alert("Unable to access camera: " + err);
                        });
                    }
                    function takeSnapshot() {
                        const video = document.getElementById('camera');
                        const canvas = document.getElementById('snapshot');
                        const context = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        canvas.style.display = 'block';
                        video.style.display = 'none';
                        document.getElementById('retakeBtn').style.display = 'inline-block';
                        document.getElementById('captureBtn').style.display = 'none';
                        document.getElementById('photoData').value = canvas.toDataURL('image/png', 1.0);
                    }
                    function retakePhoto() {
                        const canvas = document.getElementById('snapshot');
                        const video = document.getElementById('camera');
                        const context = canvas.getContext('2d');
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        canvas.style.display = 'none';
                        video.style.display = 'block';
                        document.getElementById('retakeBtn').style.display = 'none';
                        document.getElementById('captureBtn').style.display = 'inline-block';
                        document.getElementById('photoData').value = '';
                    }
                    </script>

                    <label for="photo">Upload Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required>

                    <button type="button" class="reg" id="startTimerBtn">Create Account</button>
                    <input type="hidden" name="register" value="1">

                    <p id="timerDisplay" style="font-size: 16px; font-weight: bold; color: red;"></p>
                </center>
            </form>

            <center>
                <p class="p-login-margin">Do you want to go back?</p>
                <a href="../login.php"><input type="submit" value="Back to Login" class="btn btn-primary"></a>
            </center>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("birthdate").setAttribute("max", today);

    const startTimerBtn = document.getElementById("startTimerBtn");
    const timerDisplay = document.getElementById("timerDisplay");
    const form = document.querySelector("form");
    const countdownKey = "registrationCountdownStart";
    const countdownDuration = 10; // Change to 10800 for 3 hours
    const formStorageKey = "registrationFormData";
    let countdownInterval;

    const requiredFields = [
        "firstname",
        "lastname",
        "age",
        "birthdate",
        "gender",
        "address",
        "email",
        "contact",
        "password",
        "photo_type"
    ];

    // Save form data to localStorage
    function saveFormData() {
        const data = {};
        requiredFields.forEach(id => {
            const el = document.getElementById(id);
            if (el) data[id] = el.value;
        });
        localStorage.setItem(formStorageKey, JSON.stringify(data));
    }

    // Restore form data from localStorage
    function restoreFormData() {
        const savedData = JSON.parse(localStorage.getItem(formStorageKey) || "{}");
        requiredFields.forEach(id => {
            const el = document.getElementById(id);
            if (el && savedData[id]) {
                el.value = savedData[id];
            }
        });
    }

    function startCountdown(startTime) {
        countdownInterval = setInterval(() => {
            const currentTime = Math.floor(Date.now() / 1000);
            const timeElapsed = currentTime - startTime;
            const timeLeft = countdownDuration - timeElapsed;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                localStorage.removeItem(countdownKey);
                localStorage.removeItem(formStorageKey);
                timerDisplay.textContent = "Registering resident...";
                setTimeout(() => {
                    form.submit();
                }, 1000);
            } else {
                timerDisplay.textContent = `Account will undergo verification, make sure you upload a valid Pateros ID. We will notify you when your account is ready: ${timeLeft} seconds`;
            }
        }, 1000);
    }

    function validateFormFields() {
        for (const id of requiredFields) {
            const element = document.getElementById(id);
            if (!element || element.value.trim() === "") {
                return false;
            }
        }

        const photo = document.getElementById("photo");
        if (!photo || photo.files.length === 0) return false;

        return true;
    }

    // Restore saved form data if available
    restoreFormData();

    // Add event listeners to save inputs on change
    requiredFields.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener("input", saveFormData);
        }
    });

    // Resume countdown if already in progress
    const savedStartTime = localStorage.getItem(countdownKey);
    if (savedStartTime) {
        const timeElapsed = Math.floor(Date.now() / 1000) - parseInt(savedStartTime);
        if (timeElapsed < countdownDuration) {
            startTimerBtn.disabled = true;
            startCountdown(parseInt(savedStartTime));
        } else {
            localStorage.removeItem(countdownKey);
            localStorage.removeItem(formStorageKey);
        }
    }

    startTimerBtn.addEventListener("click", function () {
        if (!validateFormFields()) {
            alert("Please fill in all required fields before submitting.");
            return;
        }

        const startTime = Math.floor(Date.now() / 1000);
        localStorage.setItem(countdownKey, startTime);
        startTimerBtn.disabled = true;
        startCountdown(startTime);
    });
});
</script>




</body>