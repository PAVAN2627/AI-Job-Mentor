<?php
$host = "localhost";
$dbname = "ai_job_mentor";
$user = "root";       // default in XAMPP
$password = "";       // leave blank if you're using XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
