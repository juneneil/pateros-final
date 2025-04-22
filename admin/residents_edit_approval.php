<?php
include 'includes/session.php';

if(isset($_POST['approval'])){
    $id = $_POST['id'];
    $approval = $_POST['approval'];

    $sql = "UPDATE residents SET approval = '$approval' WHERE id = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Approval updated successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong in updating approval';
    }
}

header('location: resident.php');
?>
