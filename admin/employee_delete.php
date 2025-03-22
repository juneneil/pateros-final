<?php
include "includes/session.php";
if (isset($_POST["delete"])) {
    $empid = $_POST["id"];
    $sql_attendance = "DELETE FROM attendance WHERE employee_id = '$empid'";
    if ($conn->query($sql_attendance)) {
        $sql = "DELETE FROM employees WHERE id = '$empid'";
        if ($conn->query($sql)) {
            $_SESSION["success"] = "Employee deleted successfully";
        } else {
            $_SESSION["error"] = "Error deleting employee: " . $conn->error;
        }
    } else {
        $_SESSION["error"] = "Error deleting attendance records: " . $conn->error;
    }
} else {
    $_SESSION["error"] = "Select item to delete first";
}
header("location: employee.php");
?>
