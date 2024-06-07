<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\PHPMailer\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\PHPMailer\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\PHPMailer\PHPMailer\src\SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendEmail'])) {
    $mail = new PHPMailer(true);

    try {
        // Server settings for PHPMailer
        $mail->SMTPDebug = SMTP::DEBUG_OFF;    // Disable verbose debug output
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dontdropassist@gmail.com';   // Your Gmail address
        $mail->Password   = 'zzqz kqfl ijry wjec';   // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Enable SSL encryption
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('jayrsantos114@gmail.com', 'Notification Service');
        $mail->addAddress($_POST['email']);   // Add a recipient

        // Content
        $mail->isHTML(true);   // Set email format to HTML
        $mail->Subject = 'Urgent: Immediate Action Required to Prevent Dropout';
        $mail->Body    = '
            <p>Dear Student,</p>
            <p>We have noticed that you are at risk of dropping out. It is crucial that you take immediate action to prevent this from happening. Here are some steps you can take:</p>
            <ul>
                <li>Reach out to your academic advisor to discuss your current situation and explore available support options.</li>
                <li>Attend all scheduled classes and complete any pending assignments.</li>
                <li>Utilize campus resources such as tutoring centers, counseling services, and study groups.</li>
                <li>Stay organized and manage your time effectively to keep up with coursework and other responsibilities.</li>
            </ul>
            <p>Your education is important, and we are here to help you succeed. Please do not hesitate to contact us if you need any assistance.</p>
            <p>Sincerely,<br>Your School Support Team</p>';
        $mail->AltBody = 'Dear Student,

We have noticed that you are at risk of dropping out. It is crucial that you take immediate action to prevent this from happening. Here are some steps you can take:
- Reach out to your academic advisor to discuss your current situation and explore available support options.
- Attend all scheduled classes and complete any pending assignments.
- Utilize campus resources such as tutoring centers, counseling services, and study groups.
- Stay organized and manage your time effectively to keep up with coursework and other responsibilities.

Your education is important, and we are here to help you succeed. Please do not hesitate to contact us if you need any assistance.

Sincerely,
Your School Support Team';

        $mail->send();
        echo '<script>alert("Notification sent successfully!"); window.location.href = "homepage.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Failed to send notification: ' . $mail->ErrorInfo . '"); window.location.href = "homepage.php";</script>';
    }
}
?>
