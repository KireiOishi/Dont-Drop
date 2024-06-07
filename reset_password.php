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
session_start(); // Start the session

$con = mysqli_connect("localhost", "root", "", "jayyr", 3306);

if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

// Check if the session variable is set
if (isset($_SESSION['reset_password_code'])) {
    $resetPasswordCode = $_SESSION['reset_password_code'];

    // Retrieve the customer ID associated with the confirmation code
    $getCustomerIdQuery = "SELECT user_id FROM user WHERE forgot_password_code = '$resetPasswordCode'";
    $result = mysqli_query($con, $getCustomerIdQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userId = $row['user_id'];

        // Assuming $newPassword is obtained from the form input
        $newPassword = mysqli_real_escape_string($con, $_POST['new_password']);

        // Update the password in the database for the specific customer
        $updatePasswordQuery = "UPDATE user SET password = '$newPassword' WHERE user_id = $userId";

        if (mysqli_query($con, $updatePasswordQuery)) {
         
            echo '<script>alert("Password Updated Successfully");window.location.href = "signin.html";</script>';
        } else {
            echo "Error updating password: " . mysqli_error($con);
        }
    } else {
        
        echo' <img src="images/no.png" id = "imeds" alt="Success" width="300" height="300">
        <div class="success-message">Invalid or expired confirmation code.</div>
        <a href="signin.html" id="home-link">Home</a>';
    }

    // Unset the session variable
    unset($_SESSION['reset_password_code']);
} else {
    echo "Invalid request.";
}

mysqli_close($con);
?>
