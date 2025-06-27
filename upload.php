<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit();
}
$userId = $_SESSION['user_id'];

$pdo = new PDO("mysql:host=localhost;dbname=ai_job_mentor", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

require_once 'mail_config.php';   // sendReportEmail()

/* ----------------------------------------------------------- */
/* 1)  POST  —  Upload + AI analysis                           */
/* ----------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {

    /* Save file */
    $baseDir = __DIR__ . "/uploads/resumes/user_$userId/";
    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);

    $file = $_FILES['resume'];
    if ($file['error'] !== UPLOAD_ERR_OK) die("❌ File upload failed.");
    $filename = basename($file['name']);
    $fullPath = $baseDir . $filename;
    move_uploaded_file($file['tmp_name'], $fullPath);

    /* Call Flask */
    $curl = curl_init("http://127.0.0.1:5000/analyze");
    curl_setopt_array($curl, [
        CURLOPT_POST            => true,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_POSTFIELDS      => [
            'user_id' => $userId,
            'resume'  => new CURLFILE($fullPath)
        ],
    ]);
    $response = curl_exec($curl);
    $http     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if (!$response || $http !== 200) die("❌ Flask Error $http<br><pre>$response</pre>");
    $data = json_decode($response, true) ?: die("❌ Invalid JSON<br><pre>$response</pre>");

    /* DB insert */
    $rec     = $data['recommendations'];
    $skills  = $data['resume_data']['skills'];
    $pdfPath = $data['pdf_path'];                       // web relative
    $pdfAbs  = __DIR__ . $pdfPath;                     // absolute for mail

    $pdo->beginTransaction();
    $pdo->prepare("INSERT INTO resumes (user_id, filename, pdf_path) VALUES (?,?,?)")
        ->execute([$userId, $filename, $pdfPath]);
    $resumeId = $pdo->lastInsertId();

    $pdo->prepare("INSERT INTO recommendations
               (resume_id, skills, recommended_jobs, learning_paths,
                skill_gaps, career_advice, average_salaries)
               VALUES (?,?,?,?,?,?,?)")
     ->execute([
        $resumeId,
        json_encode($skills),
        json_encode($rec['recommended_jobs']),
        json_encode($rec['weekly_plan']),
        json_encode($rec['skill_gaps']),
        $rec['career_advice'],
        json_encode($rec['average_salaries'])   // 🆕
     ]);

    $pdo->commit();

/* ----------------------------------------------------------- */
/* 2)  GET  —  Re-send last report (Manual Trigger)            */
/* ----------------------------------------------------------- */
} elseif (isset($_GET['send_email'])) {

    $u = $pdo->query("SELECT name,email FROM users WHERE id=$userId")->fetch(PDO::FETCH_ASSOC);
   $row = $pdo->query("
  SELECT r.pdf_path,
         rec.skills, rec.recommended_jobs, rec.learning_paths,
         rec.career_advice, rec.skill_gaps,
         rec.average_salaries                      -- 🆕
  FROM resumes r
  JOIN recommendations rec ON rec.resume_id = r.id
  WHERE r.user_id = $userId
  ORDER BY r.id DESC LIMIT 1
")->fetch(PDO::FETCH_ASSOC);


    if (!$row) die("❌ No previous report found.");

    $ok = sendReportEmail(
        $u['email'], $u['name'],
        json_decode($row['skills'], true),
        json_decode($row['recommended_jobs'], true),
        json_decode($row['learning_paths'], true),
        __DIR__ . $row['pdf_path']        // absolute path
    );
    echo "<script>alert('".($ok ? "✅ Report sent!" : "❌ Email failed")."');</script>";

    /* build $data & $rec for shared display */
    $data['pdf_path']              = $row['pdf_path'];
    $data['resume_data']['skills'] = json_decode($row['skills'], true);
    $rec = [
        'recommended_jobs' => json_decode($row['recommended_jobs'], true),
        'weekly_plan'      => json_decode($row['learning_paths'], true),
        'career_advice'    => $row['career_advice'],
        'skill_gaps'       => json_decode($row['skill_gaps'], true),
        'course_links'     => [],         // not stored
        'job_scope'        => [],          // not stored
        'average_salaries' => json_decode($row['average_salaries'], true)  // 🆕
    ];
}

/* ----------------------------------------------------------- */
/* 3)  Shared Result Display                                   */
/* ----------------------------------------------------------- */
if (!empty($data)): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Job Mentor Result</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .result-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .result-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .btn-link {
            color: #6f42c1;
            text-decoration: none;
            transition: color 0.3s;
            margin-right: 15px;
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
        canvas {
            max-width: 100%;
            height: 200px !important;
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
                        <a class="nav-link" href="history.php">View History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Result Content -->
    <div class="result-container">
        <div class="result-card">
            <h2 class="text-center mb-4 text-gradient">Analysis Complete ✅</h2>

            <div class="mb-4">
                <p><strong>Skills:</strong> <?=implode(', ', $data['resume_data']['skills']);?></p>
            </div>

            <div class="mb-4">
                <p><strong>Career Advice:</strong><br><?=nl2br(htmlspecialchars($rec['career_advice']));?></p>
            </div>

            <div class="mb-4">
                <p><strong>Recommended Job Roles:</strong>
                    <ul class="list-unstyled">
                        <?php foreach ($rec['recommended_jobs'] as $job) echo "<li class='mb-2'>$job</li>"; ?>
                    </ul>
                </p>
            </div>

            <div class="mb-4">
                <h3 class="mb-3">📘 8-Week Learning Plan:</h3>
                <ol class="list-unstyled">
                    <?php foreach ($rec['weekly_plan'] as $step) echo "<li class='mb-2'>$step</li>"; ?>
                </ol>
            </div>

            <?php if ($rec['course_links']): ?>
            <div class="mb-4">
                <p><strong>Courses:</strong>
                    <ul class="list-unstyled">
                        <?php foreach ($rec['course_links'] as $c):
                            $title = htmlspecialchars($c['title']);
                            $url   = htmlspecialchars($c['url']);
                            $type  = ucfirst($c['type']); ?>
                            <li class="mb-2"><a href="<?= $url; ?>" target="_blank" class="btn-link"><?= $title; ?></a> <small>(<?= $type; ?>)</small></li>
                        <?php endforeach; ?>
                    </ul>
                </p>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <p><strong>Skill Gaps (per job):</strong>
                    <ul class="list-unstyled">
                        <?php foreach ($rec['skill_gaps'] as $job => $gaps) echo "<li class='mb-2'><b>$job:</b> " . implode(', ', $gaps) . "</li>"; ?>
                    </ul>
                </p>
            </div>

            <?php if (!empty($rec['job_scope']) && is_array($rec['job_scope'])): ?>
            <div class="mb-4">
                <h3 class="mb-3">📊 Job-Market Demand (AI)</h3>
                <canvas id="scopeChart" style="max-width: 100%; height: 200px !important;"></canvas>
                <script>
                    const ctx = document.getElementById('scopeChart').getContext('2d');
                    const labels = <?= json_encode(array_keys($rec['job_scope'])); ?>;
                    const values = <?= json_encode(array_values($rec['job_scope'])); ?>;
                    if (labels.length > 0 && values.length > 0) {
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Demand 1-10',
                                    data: values,
                                    backgroundColor: '#6f42c1',
                                    borderColor: '#5a32a3',
                                    borderWidth: 1,
                                    barThickness: 20
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 10,
                                        title: { display: true, text: 'Demand Level' }
                                    },
                                    x: { title: { display: true, text: 'Job Roles' } }
                                },
                                plugins: {
                                    legend: { display: true }
                                }
                            }
                        });
                    } else {
                        document.getElementById('scopeChart').style.display = 'none';
                        const noData = document.createElement('p');
                        noData.textContent = 'No job market demand data available.';
                        noData.style.color = '#6f42c1';
                        noData.style.textAlign = 'center';
                        document.querySelector('.result-card').appendChild(noData);
                    }
                </script>
            </div>
            <?php else: ?>
            <div class="mb-4">
                <p style="color: #6f42c1; text-align: center;">No job market demand data available.</p>
            </div>
            <?php endif; ?>
<?php if (!empty($rec['average_salaries'])): 
      $salLabels = json_encode(array_keys($rec['average_salaries']));
      $salValues = json_encode(array_values($rec['average_salaries'])); ?>
<div class="mb-4">
    <h3 class="mb-3">💰 Average Salary (AI-Estimated)</h3>
    <canvas id="salaryChart" style="max-width: 100%; height: 200px;"></canvas>
</div>
<script>
const ctxSal = document.getElementById('salaryChart').getContext('2d');
new Chart(ctxSal,{
  type:'bar',
  data:{labels:<?=$salLabels;?>,datasets:[{
      label:'Average Salary',
      data:<?=$salValues;?>,
      backgroundColor:'#007bff'
  }]},
  options:{
      responsive:true,
      maintainAspectRatio:false,
      scales:{y:{beginAtZero:true,title:{display:true,text:'Salary in LPA'}}},
      plugins:{legend:{display:false}}
  }
});
</script>
<?php endif; ?>

            <div class="mb-4">
                <?php
                $fullURL = "http://localhost/AI-Job-Mentor" . $data['pdf_path'];
                echo "<p><strong>Download PDF:</strong> <a href='$fullURL' class='btn-link' download>📎 Resume Report</a></p>";
                echo "<p><strong>📧 Send to Email:</strong> <a href='upload.php?send_email=1' class='btn-link'>Click here</a></p>";
                ?>
            </div>

            <div class="text-center">
                <a href="dashboard.php" class="btn-link">← Back to Dashboard</a> |
                <a href="history.php" class="btn-link">📂 View History</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>