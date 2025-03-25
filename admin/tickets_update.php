<?php
include 'includes/session.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $remarks = $_POST['remarks'];

    $sql = "UPDATE ticket SET remarks = '$remarks' WHERE id = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Remarks updated successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong in updating remarks';
    }
}

header('location: tickets.php');
?>
