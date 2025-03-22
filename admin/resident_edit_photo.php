<?php
include "includes/session.php";
if (isset($_POST["upload"])) {
    $empid = $_POST["id"];
    $filename = $_FILES["photo"]["name"];
    if (!empty($filename)) {
        move_uploaded_file(
            $_FILES["photo"]["tmp_name"],
            "../images/" . $filename
        );
    }
    $sql = "UPDATE residents SET photo = '$filename' WHERE id = '$empid'";
    if ($conn->query($sql)) {
        $_SESSION["success"] = "Resident photo updated successfully";
    } else {
        $_SESSION["error"] = $conn->error;
    }
} else {
    $_SESSION["error"] = "Select resident to update photo first";
}
header("location: resident.php");
?>