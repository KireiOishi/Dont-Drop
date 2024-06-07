
<style>
      #imeds {
            width: 440px;
            height: 440px;
            margin-left: 420px;
         
        }

        .success-message {
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 85px;
            color: green;
            text-align: center;
        }

        #home-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 18px 42px;
            border-radius: 5px;
            text-align: center;
            font-size: 45px;
         
        }
</style>


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
    $email = $_POST['email'];

    $confirmationCode = generateRandomCode();

    if (!empty($email)) {
        // Check if the email exists in the database
        $checkEmailQuery = "SELECT * FROM `user` WHERE email = '$email'";
        $result = mysqli_query($con, $checkEmailQuery);

        if (mysqli_num_rows($result) > 0) {
            // Email exists, proceed with updating and sending email
            $sql = "UPDATE `user` SET forgot_password_code = '$confirmationCode' WHERE email = '$email'";

            if (mysqli_query($con, $sql)) {
             

                try {
                    echo '<script>alert("Please Check your Email for Reset Instructions");window.location.href = "confirm_account_forgot.php";</script>';
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
                $mail->setFrom($email, 'JayStore');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Reset Password';
                $mail->Body    = "Hello There! Here's the code for reseting your password, 
                please input your code: <b>$confirmationCode</b>";
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
        } else {
            // Email does not exist in the database
            echo '<script>alert("Email Not Found");window.location.href = "forgot_password.html";</script>';
        }
    } else {
        echo "Please fill in all fields.";
    }
}

mysqli_close($con);
?>