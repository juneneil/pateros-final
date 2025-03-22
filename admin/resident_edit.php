<?php
include "includes/session.php";

if (isset($_POST["edit"])) {
    $empid = $_POST["id"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $age = $_POST["age"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $birthdate = $_POST["birthdate"];
    $contact_info = $_POST["contact_info"];
    $gender = $_POST["gender"];
    
    // Handle file upload
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $photo = $_FILES["photo"];
        $photo_name = $photo["name"];
        $photo_tmp = $photo["tmp_name"];
        $photo_size = $photo["size"];
        $photo_ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
        
        // Valid file types (you can add more extensions if needed)
        $valid_ext = array("jpg", "jpeg", "png", "gif");

        if (in_array($photo_ext, $valid_ext)) {
            // Create a unique file name
            $new_photo_name = "photo_" . time() . "." . $photo_ext;
            $photo_path = "../images/" . $new_photo_name;
            
            // Move the file to the server
            if (move_uploaded_file($photo_tmp, $photo_path)) {
                // File uploaded successfully, now update the database
                $sql = "UPDATE residents SET firstname = '$firstname', lastname = '$lastname', age = '$age', email = '$email', address = '$address', birthdate = '$birthdate', contact_info = '$contact_info', gender = '$gender', photo = '$new_photo_name' WHERE id = '$empid'";
            } else {
                $_SESSION["error"] = "Failed to upload photo.";
                header("location: resident.php");
                exit;
            }
        } else {
            $_SESSION["error"] = "Invalid photo format. Allowed formats: jpg, jpeg, png, gif.";
            header("location: resident.php");
            exit;
        }
    } else {
        // If no photo was uploaded, do not update the photo field
        $sql = "UPDATE residents SET firstname = '$firstname', lastname = '$lastname', age = '$age', email = '$email', address = '$address', birthdate = '$birthdate', contact_info = '$contact_info', gender = '$gender' WHERE id = '$empid'";
    }

    // Execute the query
    if ($conn->query($sql)) {
        $_SESSION["success"] = "Resident updated successfully";
    } else {
        $_SESSION["error"] = $conn->error;
    }
} else {
    $_SESSION["error"] = "Select resident to edit first";
}

header("location: resident.php");
?>