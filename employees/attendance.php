<?php
if (isset($_POST["employee"])) {
    $output = ["error" => false];
    include "../conn.php";
    include "timezone.php";

    $employee = $_POST["employee"];
    $status = $_POST["status"];
    $target_dir = "uploads/";
    $target_file = "";

    if (isset($_FILES["employee_picture"]) && $_FILES["employee_picture"]["error"] == 0) {
        $imageFileType = strtolower(pathinfo($_FILES["employee_picture"]["name"], PATHINFO_EXTENSION));
        $newFileName = $employee . "_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $newFileName;

        if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            $output["error"] = true;
            $output["message"] = "Only JPG, JPEG, and PNG files are allowed.";
            echo json_encode($output);
            exit();
        }

        if (!move_uploaded_file($_FILES["employee_picture"]["tmp_name"], $target_file)) {
            $output["error"] = true;
            $output["message"] = "Error uploading file.";
            echo json_encode($output);
            exit();
        }
    } else {
        $output["error"] = true;
        $output["message"] = "Employee picture is required.";
        echo json_encode($output);
        exit();
    }

    $sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
    $query = $conn->query($sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $id = $row["id"];
        $date_now = date("Y-m-d");

        if ($status == "in") {
            $sql = "SELECT * FROM attendance WHERE employee_id = '$id' AND date = '$date_now' AND time_in IS NOT NULL";
            $query = $conn->query($sql);
            if ($query->num_rows > 0) {
                $output["error"] = true;
                $output["message"] = "You have already timed in today.";
            } else {
                $sched = $row["schedule_id"];
                $lognow = date("H:i:s");
                $sql = "SELECT * FROM schedules WHERE id = '$sched'";
                $squery = $conn->query($sql);
                $srow = $squery->fetch_assoc();
                $logstatus = $lognow > $srow["time_in"] ? 0 : 1;
                $sql = "INSERT INTO attendance (employee_id, date, time_in, status, picture) VALUES ('$id', '$date_now', NOW(), '$logstatus', '$target_file')";
                if ($conn->query($sql)) {
                    $output["message"] = "Time in successful: " . $row["firstname"] . " " . $row["lastname"];
                } else {
                    $output["error"] = true;
                    $output["message"] = $conn->error;
                }
            }
        } else {
            $sql = "SELECT *, attendance.id AS uid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id WHERE attendance.employee_id = '$id' AND date = '$date_now'";
            $query = $conn->query($sql);
            if ($query->num_rows < 1) {
                $output["error"] = true;
                $output["message"] = "Cannot timeout. No time-in record.";
            } else {
                $row = $query->fetch_assoc();
                if ($row["time_out"] != "00:00:00") {
                    $output["error"] = true;
                    $output["message"] = "You have already timed out today.";
                } else {
                    $sql = "UPDATE attendance SET time_out = NOW(), picture = '$target_file' WHERE id = '" . $row["uid"] . "'";
                    if ($conn->query($sql)) {
                        $output["message"] = "Time out successful: " . $row["firstname"] . " " . $row["lastname"];
                    } else {
                        $output["error"] = true;
                        $output["message"] = $conn->error;
                    }
                }
            }
        }
    } else {
        $output["error"] = true;
        $output["message"] = "Employee ID not found.";
    }
}
echo json_encode($output);
?>
