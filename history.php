<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$pdo = new PDO("mysql:host=localhost;dbname=ai_job_mentor","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
  "SELECT r.id, r.filename, r.pdf_path, r.uploaded_at,
          rec.recommended_jobs, rec.learning_paths
   FROM resumes r
   JOIN recommendations rec ON rec.resume_id = r.id
   WHERE r.user_id = ?
   ORDER BY r.uploaded_at DESC"
);
$stmt->execute([$userId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
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
        .history-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .history-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #6f42c1;
            color: white;
            font-weight: 600;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .table tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.3s;
        }
        .table a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .table a:hover {
            color: #0056b3;
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
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- History Content -->
    <div class="history-container">
        <div class="history-card">
            <h2 class="text-center mb-4 text-gradient">Your Past Reports</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Filename</th>
                        <th>Jobs Suggested</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                        <td><?=date('d M Y H:i',strtotime($row['uploaded_at']));?></td>
                        <td><?=$row['filename'];?></td>
                        <td><?=implode(", ",json_decode($row['recommended_jobs'],true));?></td>
                        <?php
                        $baseUrl = "http://localhost/AI-Job-Mentor";
                     ?>
                     <td>
                   <a href="<?= $baseUrl . htmlspecialchars($row['pdf_path']); ?>" download>Download</a>
                    </td>

                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn-link">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
?>