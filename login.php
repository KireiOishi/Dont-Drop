<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <style>
        
        .message-container {
                text-align: center;
            }

            .message-container img {
                max-width: 250px;
                height: auto;
                margin-bottom: 20px;
                margin-top: 50px;
            }

            h3 {
                margin: 0;
                color: #333;
                font-size: 50px;
                margin-bottom: 25px;
            }

            a {
                text-decoration: none;
                color: #007bff;
                font-weight: bold;
                font-size: 30px;
                border: solid #007bff 2px;
                padding: 15px;
                border-radius: 5px;
            }
    </style>
</head>
<body>
    
</body>
</html>
<?php
session_start(); // Start the session

$con = mysqli_connect("localhost", "root", "", "jayyr", 3306);

if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Check if the email exists in the database
        $checkEmailQuery = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($con, $checkEmailQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Check if the account is confirmed
            if ($user['confirmation_status'] === 'confirmed') {
                // Check if the account is blocked
                if ($user['block_status'] == 1) {
           
                    echo '<script>alert("You Account is BLOCKED!!!");window.location.href = "homepage.php";</script>';
                } else {
                    // Check if the password is correct
                    if ($user['password'] == $password) {
                        // After validating credentials
                        $_SESSION['user_id'] = $user['user_id']; // assuming customer_id is the unique identifier
                        $_SESSION['logged_in'] = true;
                        

                        // Set a JavaScript variable to indicate successful login
    echo '<script>var loggedIn = true;</script>';
                        
                        // You can set session variables or perform other login actions here

                         // Redirect to homepage.php after a delay
    echo '<script>
    setTimeout(function(){
        if(loggedIn){
            alert("Logged In Successfully!");
            window.location.href = "homepage.php";
        }
    }, 1000); // Adjust the delay (in milliseconds) as needed
  </script>';
exit();
                    } else {
                        echo '<script>alert("Invalid Password");window.location.href = "signin.html";</script>';
                     }
                }
            } else {
           
                
                echo '<script>alert("Account not registered.");window.location.href = "signin.html";</script>';
            }
        } else {
           
           echo '<script>alert("Email not found. Please register.");window.location.href = "signin.html";</script>';
            
        }
    } else {
        
        echo '<script>alert("Please enter both email and password.");window.location.href = "signin.html";</script>';
    }
}

// Close the database connection
mysqli_close($con);
?>