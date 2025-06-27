<?php
// mail_config.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';   // Composer autoloader

function sendOTP($toEmail, $toName, $otp) {
    $mail = new PHPMailer(true);

    try {
        // ---------- SMTP SETTINGS ----------
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';      // e.g. smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pavanmalith3@gmail.com';
        $mail->Password   = 'qsqa drxj xflr ergx';   // Gmail App Password (see tip below)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        // ------------------------------------

        // Sender & recipient
        $mail->setFrom('pavanmalith3@gmail.com', 'AI Job Mentor');
        $mail->addAddress($toEmail, $toName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your AI Job Mentor OTP';
        $mail->Body    = "<h3>Hello $toName!</h3>
                          <p>Your one-time password (OTP) is:</p>
                          <h2>$otp</h2>
                          <p>Enter this on the verification page to activate your account.</p>";

        $mail->send();
        return true;

    } catch (Exception $e) {
        // You can log $mail->ErrorInfo here
        return false;
    }
}
 function sendReportEmail($toEmail, $toName, $skills, $jobs, $learning, $pdfPath) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pavanmalith3@gmail.com';
        $mail->Password   = 'qsqa drxj xflr ergx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('pavanmalith@gmail.com', 'AI Job Mentor');
        $mail->addAddress($toEmail, $toName);

        // Attach PDF report
        $mail->addAttachment($pdfPath);

        // Message content
        $mail->isHTML(true);
        $mail->Subject = "Your AI Job Mentor Report";
        $mail->Body = "
            <p>Hi <strong>$toName</strong>,</p>
            <p>Here is your personalized career report:</p>
            <ul>
              <li><strong>Skills Found:</strong> " . implode(", ", $skills) . "</li>
              <li><strong>Job Suggestions:</strong> " . implode(", ", $jobs) . "</li>
              <li><strong>Learning Paths:</strong> <ul><li>" . implode("</li><li>", $learning) . "</li></ul></li>
            </ul>
            <p>Check the attached PDF for full details.</p>
            <p>Thanks for using AI Job Mentor!</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Log error or display message if needed
        return false;
    }
}
