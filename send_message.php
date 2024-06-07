<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\PHPMailer\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\PHPMailer\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\PHPMailer\PHPMailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email address. Please provide a valid email address.");</script>';
        exit; // Stop execution if email is invalid
    }

    // Email address where you want to receive the messages
    $to = "jayrsantos114@gmail.com";

    // Subject of the email
    $subject = "Message from $name";

    // Email body
    $body = "Name: $name\n";
    $body .= "Email: $email\n\n";
    $body .= "Message:\n$message";

    $mail = new PHPMailer(true);

    try {
        // Server settings for PHPMailer
        $mail->SMTPDebug = SMTP::DEBUG_OFF;    // Disable verbose debug output
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jayrsantos114@gmail.com';   // Your Gmail address
        $mail->Password   = 'ibggjtsazotzwrvp';   // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Enable SSL encryption
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('jayrsantos114@gmail.com', 'Notification Service');
        $mail->addAddress($to);   // Add your email address

        // Content
        $mail->isHTML(false);   // Set email format to plain text
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo '<script>alert("Your message has been sent. We will get back to you soon."); window.location.href = "contact.html";</script>';
    } catch (Exception $e) {
        echo '<script>alert("There was an error sending your message: ' . $mail->ErrorInfo . '"); window.location.href = "contact..html";</script>';
    }
}
?>
