<?php
session_start();

// DB connection
$host = "localhost";
$dbname = "ai_job_mentor";
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get user email from session
$email = $_SESSION['otp_email'] ?? null;

if (!$email) {
    die("Session expired. Please register again. <a href='register.php'>Register</a>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT otp_code FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $entered_otp == $user['otp_code']) {
        $conn->prepare("UPDATE users SET otp_verified = 1 WHERE email = ?")->execute([$email]);
        echo "<div class='alert alert-success text-center'>✅ OTP Verified! <a href='login.php'>Click here to login</a></div>";
        unset($_SESSION['otp_email']);
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Invalid OTP. Try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - AI Job Mentor</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6f42c1, #007bff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .otp-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .otp-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: none;
        }
        .btn-primary {
            background: #6f42c1;
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            transition: transform 0.2s, background 0.3s;
        }
        .btn-primary:hover {
            background: #5a32a3;
            transform: scale(1.05);
        }
        .btn-secondary {
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-secondary:hover {
            transform: scale(1.05);
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .text-gradient {
            background: linear-gradient(45deg, #6f42c1, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="otp-card">
            <h2 class="text-center mb-4 text-gradient">AI Job Mentor</h2>
            <h4 class="text-center mb-4">Verify OTP</h4>
            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" name="otp" class="form-control" placeholder="6-digit OTP" required>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Verify OTP</button>
                <a href="login.php" class="btn btn-secondary">Login</a>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>