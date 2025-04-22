<?php
if (isset($_POST["employee"])) {
    $output = ["error" => false];
    include "../conn.php";
    include "timezone.php";
    $employee = $_POST["employee"];
    $status = $_POST["status"];
    $target_dir = "uploads/";
    $target_file = "";
    date_default_timezone_set("Asia/Manila");
    $current_datetime = date("Y-m-d H:i:s");
    $date_now = date("Y-m-d");
    $time_now = date("H:i:s");
    // Validate image upload
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

    // Check if employee exists
    $sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
    $query = $conn->query($sql);
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $id = $row["id"];
        if ($status == "in") {
            // Check if already timed in
            $sql = "SELECT * FROM attendance WHERE employee_id = '$id' AND date = '$date_now' AND time_in IS NOT NULL";
            $query = $conn->query($sql);
            if ($query->num_rows > 0) {
                $output["error"] = true;
                $output["message"] = "You have already timed in today.";
            } else {
                $sched = $row["schedule_id"];
                $sql = "SELECT * FROM schedules WHERE id = '$sched'";
                $squery = $conn->query($sql);
                $srow = $squery->fetch_assoc();
                $scheduled_time_in = $srow["time_in"];
                $logstatus = ($time_now > $scheduled_time_in) ? 0 : 1;
                // Insert time-in record
                $sql = "INSERT INTO attendance (employee_id, date, time_in, status, picture_in) 
                        VALUES ('$id', '$date_now', '$current_datetime', '$logstatus', '$target_file')";
                if ($conn->query($sql)) {
                    $output["message"] = "Time in successful: " . $row["firstname"] . " " . $row["lastname"];
                } else {
                    $output["error"] = true;
                    $output["message"] = $conn->error;
                }
            }
        } else {
            // Time-out
            $sql = "SELECT *, attendance.id AS uid FROM attendance WHERE employee_id = '$id' AND date = '$date_now'";
            $query = $conn->query($sql);
            if ($query->num_rows < 1) {
                $output["error"] = true;
                $output["message"] = "Cannot timeout. No time-in record.";
            } else {
                $row = $query->fetch_assoc();
                if ($row["time_out"] != "00:00:00" && $row["time_out"] != null) {
                    $output["error"] = true;
                    $output["message"] = "You have already timed out today.";
                } else {
                    // Calculate number of hours worked
                    $in_datetime = new DateTime($row["time_in"]);
                    $out_datetime = new DateTime($current_datetime);
                    // Calculate the difference in seconds
                    $seconds = $out_datetime->getTimestamp() - $in_datetime->getTimestamp();
                    // Convert to hours
                    $hours_worked = $seconds / 3600;
                    // Round to the nearest whole number (no decimals)
                    $hours_worked = round($hours_worked);
                    // Update attendance record
                    $sql = "UPDATE attendance 
                        SET time_out = '$current_datetime', picture_out = '$target_file', num_hr = '$hours_worked' 
                        WHERE id = '" . $row["uid"] . "'";
                         $output["message"] = "Time out successful: " . $row["firstname"] . " " . $row["lastname"];
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
    echo json_encode($output);
}
?>
