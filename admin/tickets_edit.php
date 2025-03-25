<?php
include "includes/session.php";

if (isset($_POST["edit"])) {
    $id = $_POST["id"];
    $remarks = $_POST["remarks"];

    $sql = "UPDATE ticket SET remarks = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $remarks, $id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Ticket remarks updated successfully";
    } else {
        $_SESSION["error"] = "Failed to update remarks: " . $conn->error;
    }

    $stmt->close();
} else {
    $_SESSION["error"] = "Fill up edit form first";
}

header("location: tickets.php");
?>
