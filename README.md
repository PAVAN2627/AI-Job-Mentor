# AI Job Mentor - Intelligent Career Guidance Through Resume Analysis

AI Job Mentor is a web-based application that provides personalized career guidance by analyzing user resumes using artificial intelligence. The system offers automated job recommendations, skill assessments, and customized learning paths to help users advance their careers effectively.

The application combines PHP-based web interface with a Python AI engine to deliver comprehensive career guidance. It features secure user authentication, resume processing, automated skill extraction, job role matching, and generates detailed PDF reports with personalized recommendations. The system also maintains a history of analyses and provides email notifications for important updates and reports.

## Repository Structure
```
.
├── ai_engine/                  # Python-based AI processing components
│   ├── app.py                 # Main Flask application entry point
│   ├── azure_client.py        # Azure AI services integration
│   ├── job_recommender.py     # Job matching algorithm implementation
│   ├── pdf_generator.py       # PDF report generation utility
│   ├── requirements.txt       # Python package dependencies
│   └── resume_analyzer.py     # Resume text extraction and analysis
├── composer.json              # PHP dependencies configuration
├── composer.lock              # PHP dependencies lock file
├── dashboard.php              # Main user interface after login
├── db_config.php              # Database connection configuration
├── history.php                # User's past analysis reports view
├── login.php              # User authentication interface
├── logout.php            # Session termination handler
├── mail_config.php      # Email service configuration
├── profile.php         # User profile management
├── register.php       # New user registration
├── upload.php        # Resume file upload handler
└── verify_otp.php   # OTP verification for registration
```

## Usage Instructions
### Prerequisites
- PHP 7.4 or higher
- Python 3.8 or higher
- MySQL 5.7 or higher
- Composer (PHP package manager)
- pip (Python package manager)
- SMTP server access for email functionality

Required PHP Extensions:
- PDO
- PDO_MySQL
- OpenSSL
- fileinfo

### Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd ai-job-mentor
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Python dependencies:
```bash
cd ai_engine
pip install -r requirements.txt
```

4. Configure the database:
```sql
CREATE DATABASE ai_job_mentor;
USE ai_job_mentor;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    otp VARCHAR(6),
    otp_verified BOOLEAN DEFAULT FALSE
);

CREATE TABLE resumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    filename VARCHAR(255),
    pdf_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resume_id INT,
    recommended_jobs JSON,
    learning_paths JSON,
    FOREIGN KEY (resume_id) REFERENCES resumes(id)
);
```

5. Configure environment:
- Copy `mail_config.php.example` to `mail_config.php` and update SMTP settings
- Update database credentials in `db_config.php`

### Quick Start
1. Start the AI engine:
```bash
cd ai_engine
python app.py
```

2. Start the PHP development server:
```bash
php -S localhost:8000
```

3. Access the application at `http://localhost:8000`

### More Detailed Examples
1. User Registration:
```php
POST /register.php
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "secure_password"
}
```

2. Resume Upload:
```php
POST /upload.php
Content-Type: multipart/form-data
{
    "resume": "<pdf_file>"
}
```

### Troubleshooting
1. SMTP Connection Issues
- Error: "SMTP connect() failed"
- Solution: 
  ```php
  // Check mail_config.php settings
  $mail->SMTPDebug = 2; // Enable verbose debug output
  ```

2. PDF Generation Errors
- Check write permissions in the upload directory
- Verify PDF library installation:
  ```bash
  pip install --upgrade fpdf PyMuPDF
  ```

## Data Flow
The application processes user data through a multi-stage pipeline, from resume upload to recommendation generation.

```ascii
User -> Upload Resume -> Python AI Engine -> Analysis Results
  |                                               |
  |                                               v
  |                                         Generate PDF
  v                                               |
Database <-------- Store Results <----------------+
  |
  v
Email Notification
```

Key Component Interactions:
1. Web interface collects user input and handles authentication
2. Resume files are processed by the Python AI engine using spaCy and scikit-learn
3. Job recommendations are generated using machine learning algorithms
4. PDF reports are created using FPDF library
5. MySQL database stores user data and analysis results
6. PHPMailer handles email notifications and report delivery
7. Session management ensures secure user access
