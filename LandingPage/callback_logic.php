<?php
include "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($phone) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO callback (name, phone_number, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $message);
        
        if ($stmt->execute()) {
            echo "Data inserted successfully.";
            header("Location: index.php?status=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

$conn->close();
?>
