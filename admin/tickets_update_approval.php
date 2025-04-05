<?php
include 'includes/session.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $approval = $_POST['approval'];

    $sql = "UPDATE ticket SET approval = '$approval' WHERE id = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Approval updated successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong in updating approval';
    }
}

header('location: tickets.php');
?>
