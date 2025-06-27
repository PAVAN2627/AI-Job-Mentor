<?php
session_start();

/* ---------- DB CONNECTION ---------- */
$host = "localhost";
$dbname = "ai_job_mentor";
$user = "root";
$password = "";
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/* ----------------------------------- */

require_once 'mail_config.php';   // <-- include the helper

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = $_POST['name']  ?? '';
    $email    = $_POST['email'] ?? '';
    $pwdHash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $otp      = rand(100000, 999999);

    // Duplicate check
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $msg = "❌ Email already registered.";
    } else {
        // Insert and email OTP
        $stmt = $conn->prepare(
            "INSERT INTO users (name,email,password,otp_code) VALUES (?,?,?,?)"
        );
        $stmt->execute([$name, $email, $pwdHash, $otp]);

        if (sendOTP($email, $name, $otp)) {
            $_SESSION['otp_email'] = $email;
            header("Location: verify_otp.php");   // forward user to OTP page
            exit();
        } else {
            $msg = "⚠️ Could not send OTP e-mail. Try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AI Job Mentor</title>
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
        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .register-card {
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
    <div class="register-container">
        <div class="register-card">
            <h2 class="text-center mb-4 text-gradient">AI Job Mentor</h2>
            <h4 class="text-center mb-4">Create Account</h4>
            <?php if(!empty($msg)) echo "<div class='alert alert-danger text-center'>$msg</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input name="email" type="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Register</button>
                <a href="login.php" class="btn btn-secondary">Login</a>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>