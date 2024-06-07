<?php
session_start(); // Start the session

$con = mysqli_connect("localhost", "root", "", "jayyr", 3306);

if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get confirmation code from the form
    $userConfirmationCode = $_POST['confirmation_code'];

    // Check if the confirmation code is not empty
    if (!empty($userConfirmationCode)) {
        // Check confirmation code in a case-insensitive manner
        $checkCodeQuery = "SELECT * FROM user WHERE forgot_password_code = '$userConfirmationCode'";
        
        $result = mysqli_query($con, $checkCodeQuery);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // Store the confirmation code in the session
                $_SESSION['reset_password_code'] = $userConfirmationCode;

                // Redirect to reset_password.html
                header("Location: reset_password.html");
                exit();
            } else {
                $invalidCode = true;
            }
        } else {
            $errorMessage = "Query failed - " . mysqli_error($con);
        }
    } else {
        $errorMessage = "Please enter a confirmation code";
    }
}

// Close the database connection
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Account</title>
    <style>
        body {
            background-color: #f8f9fa;
            color: #495057;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }

        img {
            max-width: 150px;
            margin-bottom: 20px;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-top: 10px;
            font-size: 16px;
        }

        input[type="text"] {
            margin-top: 5px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 100%;
        }

        button {
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="form-container">
    <img src="images/dont.png" alt="Logo">
    
    <?php if (isset($invalidCode) && $invalidCode) { ?>
        echo '<script>alert("Invalid Code");window.location.href = "confirm_account_forgot.php";</script>';
    <?php } 
    else { ?>
        <h1>Verify Your Account</h1>
        <form action="confirm_account_forgot.php" method="POST">
            <label for="confirmation_code">Confirmation Code:</label>
            <input type="text" name="confirmation_code" required>
            <button type="submit">Confirm Account</button>
        </form>
    <?php } ?>
</div>
</body>
</html>
