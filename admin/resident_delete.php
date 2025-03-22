<?php
include "includes/session.php";
if (isset($_POST["delete"])) {
    $empid = $_POST["id"];
    $sql_attendance = "DELETE FROM residents WHERE resident_id = '$empid'";
    if ($conn->query($sql_attendance)) {
        $sql = "DELETE FROM residents WHERE id = '$empid'";
        if ($conn->query($sql)) {
            $_SESSION["success"] = "Resident deleted successfully";
        } else {
            $_SESSION["error"] = "Error deleting resident: " . $conn->error;
        }
    } else {
        $_SESSION["error"] = "Error deleting attendance records: " . $conn->error;
    }
} else {
    $_SESSION["error"] = "Select item to delete first";
}
header("location: resident.php");
?>
