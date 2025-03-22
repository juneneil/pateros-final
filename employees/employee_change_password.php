<?php

$pageTitle = "Change Password";
$WithEmployeeCSS = True;
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: employeeLogin.php");
    exit();
}
$user_id = $_SESSION["user_id"];
$current_password = $_SESSION["lastname"];
include "conn.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["new_password"])) {
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
        if ($new_password === $confirm_password) {
            if ($new_password !== $current_password) {
                $sql = "UPDATE employees SET password = ?, password_changed = 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $new_password, $user_id);
                if ($stmt->execute()) {
                    echo json_encode(["success" => true]);
                    exit();
                } else {
                    echo json_encode(["error" => "Failed to update password. Please try again."]);
                    exit();
                }
            } else {
                echo json_encode(["error" => "New password cannot be the same as the default password (lastname)."]);
                exit();
            }
        } else {
            echo json_encode(["error" => "Passwords do not match. Please try again."]);
            exit();
        }
    }
}
include 'header.php';
?>
<body>
<style>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40vh;
    flex-direction: column;
}
.login-container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
    background: white;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
}
button.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
}
button.btn-primary:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}
button.btn-primary::after {
    content: "";
    position: absolute;
    width: 300%;
    height: 300%;
    top: 50%;
    left: 50%;
    background: rgba(255, 255, 255, 0.3);
    transition: all 0.6s ease-out;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
}
button.btn-primary:active::after {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0;
}
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40vh;
    flex-direction: column;
}
.login-container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
    background: white;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
}
#confettiCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none;
}
.success-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 20px;
    border-radius: 10px;
    font-size: 20px;
    text-align: center;
    display: none;
}
#countdown {
    position: fixed;
    top: 35%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 40px;
    color: white;
    background: rgba(0, 0, 0, 0.8);
    padding: 20px;
    border-radius: 10px;
    display: none;
    font-weight: bold;
}

</style>

<canvas id="confettiCanvas"></canvas>
<div class="container">
    <div class="image">
        <img src="images/PaterosMunicipal.jpg" alt="Pateros Building">
    </div>
    <div class="login-container">
        <h2>Change Password</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form method="post" id="passwordForm">
            <div class="">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
            </div>
            <div class="">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<div id="countdown"></div>

<div class="success-message" id="successMessage">
    🎉 Congrats! You have successfully changed your password! 🎉
</div>

<script>
function startConfetti() {
    const canvas = document.getElementById("confettiCanvas");
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let confetti = [];
    const colors = ["#FF0000", "#00FF00", "#0000FF", "#FFFF00", "#FF00FF", "#00FFFF"];

    for (let i = 0; i < 100; i++) {
        confetti.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height - canvas.height,
            r: Math.random() * 6 + 2,
            d: Math.random() * 2 + 2,
            color: colors[Math.floor(Math.random() * colors.length)]
        });
    }

    function drawConfetti() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        confetti.forEach((c, i) => {
            ctx.beginPath();
            ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2, false);
            ctx.fillStyle = c.color;
            ctx.fill();
            c.y += c.d;
            if (c.y > canvas.height) confetti[i].y = -10;
        });
    }

    function animateConfetti() {
        drawConfetti();
        requestAnimationFrame(animateConfetti);
    }

    animateConfetti();

    setTimeout(() => {
        canvas.style.display = "none";
    }, 9000);
}

function showSuccessMessage() {
    const message = document.getElementById("successMessage");
    message.style.display = "block";
    setTimeout(() => {
        message.style.display = "none";
    }, 7000);
}

document.getElementById('passwordForm').addEventListener('submit', function(event) {
    event.preventDefault();
    startConfetti();
    showSuccessMessage();
    fetch(window.location.href, {
        method: 'POST',
        body: new FormData(event.target),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setTimeout(() => {
                window.location.href = "index.php";
            }, 8000);
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

document.getElementById('passwordForm').addEventListener('submit', function(event) {
    event.preventDefault();
    startConfetti();
    showSuccessMessage();
    startCountdown();
    fetch(window.location.href, {
        method: 'POST',
        body: new FormData(event.target),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


// Countdown Timer
function startCountdown() {
    const countdownElement = document.getElementById("countdown");
    countdownElement.style.display = "block";
    let countdown = 7;
    
    const interval = setInterval(() => {
        countdownElement.textContent = countdown;
        countdown--;
        if (countdown < 0) {
            clearInterval(interval);
            window.location.href = "index.php";
        }
    }, 1000);
}
</script>
</body>
