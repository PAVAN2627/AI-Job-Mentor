<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$pdo = new PDO("mysql:host=localhost;dbname=ai_job_mentor", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get user info
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get latest recommended job roles
$recQuery = $pdo->prepare("
    SELECT r.recommended_jobs
    FROM recommendations r
    JOIN resumes rs ON rs.id = r.resume_id
    WHERE rs.user_id = ?
    ORDER BY rs.id DESC
    LIMIT 1
");
$recQuery->execute([$userId]);
$rec = $recQuery->fetch(PDO::FETCH_ASSOC);
$recommendedJobs = $rec ? json_decode($rec['recommended_jobs'], true) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            padding-top: 80px;
        }
        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
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
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .profile-card h2 {
            background: linear-gradient(45deg, #6f42c1, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            text-align: center;
        }
        .profile-card p {
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .profile-card ul {
            list-style-type: none;
            padding-left: 0;
        }
        .profile-card ul li {
            margin-bottom: 8px;
            padding-left: 15px;
            position: relative;
        }
        .profile-card ul li:before {
            content: "•";
            color: #6f42c1;
            position: absolute;
            left: 0;
        }
        .profile-card em {
            color: #666;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(45deg, #6f42c1, #007bff);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(111, 66, 193, 0.4);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Content -->
    <div class="profile-container">
        <div class="profile-card">
            <h2>👤 Profile Information</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

            <p><strong>Recommended Jobs:</strong><br>
            <?php if ($recommendedJobs): ?>
                <ul>
                    <?php foreach ($recommendedJobs as $job): ?>
                        <li><?= htmlspecialchars($job) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <em>No recommendations yet.</em>
            <?php endif; ?>
            </p>
            <div class="text-center">
                <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
?>