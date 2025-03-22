<?php
include "includes/session.php";
if (isset($_POST["add"])) {
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $age = $_POST["age"];
    $address = $_POST["address"];
    $birthdate = $_POST["birthdate"];
    $contact_info = $_POST["contact_info"];
    $gender = $_POST["gender"];
    $filename = $_FILES["photo"]["name"];
    $password = $lastname; // Set default password to last name

    // Handle file upload
    if (!empty($filename)) {
        move_uploaded_file($_FILES["photo"]["tmp_name"], "../images/" . $filename);
    }

    // Generate resident ID (3 letters + 9 digits)
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $numbers = "0123456789";
    $resident_id =
        substr(str_shuffle($letters), 0, 3) .
        substr(str_shuffle($numbers), 0, 9);

    // **Fixed SQL query: Ensure all columns have corresponding values**
    $sql = "INSERT INTO residents (resident_id, age, email, firstname, lastname, address, birthdate, contact_info, gender, photo, password, created_on) 
            VALUES ('$resident_id', '$age', '$email', '$firstname', '$lastname', '$address', '$birthdate', '$contact_info', '$gender', '$filename', '$password', NOW())";

    if ($conn->query($sql)) {
        $_SESSION["success"] = "Resident added successfully";
        $_SESSION["user_id"] = $conn->insert_id;
        $_SESSION["email"] = $email;
        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
    } else {
        $_SESSION["error"] = $conn->error;
    }
} else {
    $_SESSION["error"] = "Fill up add form first";
}

header("location: resident.php");
?>