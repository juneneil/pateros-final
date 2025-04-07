<?php
include "conn.php";

$success = false; // flag to trigger the message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($phone) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO callback (name, phone_number, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $message);

        if ($stmt->execute()) {
            $success = true; // set the flag
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
<section class="contact_section" id="CallBack">
<?php if ($success): ?>
    <div id="callback-success" style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">
        ✅ Your message has been sent successfully!
    </div>

    <script>
        window.onload = () => {
            const successBox = document.getElementById("callback-success");
            if (successBox) {
                successBox.scrollIntoView({ behavior: "smooth" });
            }
        };
    </script>
<?php endif; ?>
<h2>
    Request A Call Back
</h2>
<form method="post" action="index.php">
    <div>
        <input type="text" name="name" placeholder="Name" required />
    </div>
    <div>
        <input type="number" name="phone_number" placeholder="Phone Number" required />
    </div>
    <div>
        <input type="email" name="email" placeholder="Email" required />
    </div>
    <div>
        <input type="text" class="message-box" name="message" placeholder="Message" required />
    </div>
    <div class="d-flex">
        <button type="submit">SEND</button>
    </div>
</form>
<div class="map_container">
    <div class="map">
        <div id="googleMap" style="width:100%;height:300px;"></div>
    </div>
</div>
</section>
