<?php
// include 'includes/session.php';

// if(isset($_POST['id'])){
//     $id = $_POST['id'];
//     $sql = "SELECT id, remarks FROM ticket WHERE id = '$id'";
//     $query = $conn->query($sql);
//     $row = $query->fetch_assoc();
//     echo json_encode($row);
// }
?>


<?php
include 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Validate if $id is a number (since it's an auto-increment ticket ID)
    if (!is_numeric($id)) {
        echo json_encode(['error' => 'Invalid ticket ID.']);
        exit;
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, remarks, approval FROM ticket WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Ticket not found.']);
        }
    } else {
        echo json_encode(['error' => 'Query failed.']);
    }

    $stmt->close();
}
else {
    echo json_encode(['error' => 'ID not set.']);
}
?>
