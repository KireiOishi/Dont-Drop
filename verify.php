<?php
$con = mysqli_connect("localhost", "root", "", "jayyr", 3306);

if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

function generateRandomCode($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\Xampp\htdocs\PHPMailer\PHPMailer\src\Exception.php';
require 'C:\Xampp\htdocs\PHPMailer\PHPMailer\src\PHPMailer.php';
require 'C:\Xampp\htdocs\PHPMailer\PHPMailer\src\SMTP.php';

$mail = new PHPMailer(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    

    $confirmationCode = generateRandomCode();

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM `user` WHERE email = '$email'";
    $result = mysqli_query($con, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        // Email already exists in the database
        echo '<script>alert("Email is Already associated with an account."); window.location.href = "signup.html";</script>';
    } else {
        // Email does not exist, proceed with registration
        $sql = "INSERT INTO `user` (`first_name`, `last_name`,`subject`, `email`, `password`, `address`, `confirmation_code`)
                VALUES ('$firstname', '$lastname', '$subject', '$email', '$password', '$address',  '$confirmationCode')";

        if (mysqli_query($con, $sql)) {
         
            try {
		echo '<script>alert("Please Check your Email for Confirmation");window.location.href = "confirm_account.php";</script>';
                // Server settings for PHPMailer
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'jayrsantos114@gmail.com';
                $mail->Password   = 'ibggjtsazotzwrvp';
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465;

                // Recipients and email content
                $mail->setFrom($email, 'DontDrop');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Dont Drop ACCOUNT REGISTRATION';
                $mail->Body    = "Thank you for choosing DONTDROP!. To verify your account, 
                please input your confirmation code: <b>$confirmationCode</b>";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                // Send email
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}

mysqli_close($con);
?>
