<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Job Mentor - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6f42c1, #007bff);
            color: #333;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: background-color 0.3s;
        }
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
        }
        .navbar-brand img {
            height: 40px;
            transition: transform 0.3s;
        }
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        .nav-link {
            color: #333;
            font-weight: 500;
            margin-left: 15px;
            transition: color 0.3s, transform 0.3s;
        }
        .nav-link:hover {
            color: #6f42c1;
            transform: translateY(-2px);
        }
        .hero {
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            background: url('https://via.placeholder.com/1200x700.png?text=AI+Job+Mentor+Hero') no-repeat center center/cover;
            animation: fadeIn 1s ease-in-out;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(45deg, #6f42c1, #007bff);
            border: none;
            padding: 10px 25px;
            font-size: 1rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(111, 66, 193, 0.4);
        }
        .section {
            padding: 40px 20px;
            background: white;
            margin: 20px auto;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            animation: slideUp 1s ease-in-out;
        }
        .section h2 {
            color: #6f42c1;
            margin-bottom: 20px;
            text-align: center;
        }
        .accordion-button {
            background-color: #f8f9fa;
            color: #333;
            transition: background-color 0.3s;
        }
        .accordion-button:not(.collapsed) {
            background-color: #6f42c1;
            color: white;
        }
        .accordion-button:hover {
            background-color: #e9ecef;
        }
        .service-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .team-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 15px 0;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(111, 66, 193, 0.2);
        }
        .team-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
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
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="uploads/aijobmentorlogo.jpg" alt="AI Job Mentor Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#team">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Signup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to AI Job Mentor</h1>
            <p>Transform your career with AI-powered job recommendations and personalized learning plans.</p>
            <a href="upload.php" class="btn btn-primary">Get Started</a>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="section">
        <h2>About Us</h2>
        <div class="row">
            <div class="col-md-6">
                <p>AI Job Mentor is a cutting-edge platform designed to empower individuals in their career journey. Using advanced AI technology, we analyze your skills, recommend tailored job roles, and provide actionable learning paths to help you succeed in today’s competitive job market. Our mission is to bridge the gap between your potential and your dream career.</p>
            </div>
            <div class="col-md-6">
                <img src="uploads/aijob.jpg" alt="AI Technology" class="img-fluid rounded">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section">
        <h2>Our Services</h2>
        <div class="accordion" id="servicesAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#service1" aria-expanded="true" aria-controls="service1">
                        <img src="uploads/resumeiconnew.png" alt="Resume Icon" class="service-icon me-2">
                        Resume Analysis
                    </button>
                </h2>
                <div id="service1" class="accordion-collapse collapse show" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        Upload your resume to receive a detailed analysis of your skills and gaps, helping you understand where to focus your efforts.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service2" aria-expanded="false" aria-controls="service2">
                        <img src="uploads/jobicon.png" alt="Jobs Icon" class="service-icon me-2">
                        Job Recommendations
                    </button>
                </h2>
                <div id="service2" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        Get personalized job role suggestions based on your skills and current market demand, with demand scores from 1-10.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service3" aria-expanded="false" aria-controls="service3">
                        <img src="uploads/learningicon.png" alt="Learning Icon" class="service-icon me-2">
                        Learning Plans
                    </button>
                </h2>
                <div id="service3" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        Receive an 8-week learning plan with weekly tasks to build the skills needed for your target job roles.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service4" aria-expanded="false" aria-controls="service4">
                        <img src="uploads/certifictionicon.jpeg" alt="Cert Icon" class="service-icon me-2">
                        Certifications
                    </button>
                </h2>
                <div id="service4" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        Access recommended certifications like AWS Certified Solutions Architect and Google Professional Data Engineer to boost your credentials.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service5" aria-expanded="false" aria-controls="service5">
                        <img src="uploads/scope.png" alt="Scope Icon" class="service-icon me-2">
                        Jobs with Current Scope
                    </button>
                </h2>
                <div id="service5" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        Explore high-demand roles such as AI/ML Engineer, Data Scientist, and Cloud Architect in 2025.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service6" aria-expanded="false" aria-controls="service6">
                        <img src="uploads/salary.png" alt="Salary Icon" class="service-icon me-2">
                        Average Salary Analysis
                    </button>
                </h2>
                <div id="service6" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        Get insights into average salaries, e.g., $120,000/year for AI/ML Engineer, $110,000/year for Data Scientist, and $130,000/year for Cloud Architect.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="section">
        <h2>Our Team</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="team-card">
                    <img src="uploads/pavanlogo.jpg" alt="Team Member 1" class="img-fluid">
                    <h4>Pavan Mali</h4>
                    <p><strong>College:</strong> MMCOE Pune</p>
                    <p><strong>Role:</strong> Backend Developer</p>
                    <p><strong>Email:</strong> <a href="mailto:john.doe@aijobmentor.com">pavanmali0281@gail.com</a></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="team-card">
                    <img src="uploads/Sakshi.jpg" alt="Team Member 2" class="img-fluid">
                    <h4>Sakshi Pawar</h4>
                    <p><strong>College:</strong> VIT Pune</p>
                    <p><strong>Role:</strong> Fronted Developer</p>
                    <p><strong>Email:</strong> <a href="mailto:jane.smith@aijobmentor.com">sakshipawar0313@gmail.com</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="section">
        <h2>Contact Us</h2>
        <div class="row">
            <div class="col-md-6">
                <p>Have questions? Reach out to us at <a href="mailto:support@aijobmentor.com">support@aijobmentor.com</a> or call us at +1-800-555-1234. We’re here to assist you 24/7!</p>
            </div>
           
        </div>
        <a href="mailto:support@aijobmentor.com" class="btn-back">Send Email</a>
        
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for nav links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
<?php
?>