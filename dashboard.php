<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AI Job Mentor</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6f42c1, #007bff);
            min-height: 100vh;
            margin: 0;
        }
        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            background: linear-gradient(45deg, #6f42c1, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-link {
            color: #333;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #6f42c1;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 80px auto 20px;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .dashboard-card {
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
        .btn-link {
            color: #6f42c1;
            text-decoration: none;
            transition: color 0.3s;
        }
        .btn-link:hover {
            color: #5a32a3;
            text-decoration: underline;
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
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">AI Job Mentor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">View Past Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="dashboard-card">
            <div class="welcome-section">
                <h2 class="text-gradient">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
            </div>
            <h4 class="mb-4">Upload Resume</h4>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" name="resume" class="form-control" accept=".pdf,.docx" required>
                </div>
                <button type="submit" class="btn btn-primary">Analyze</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>