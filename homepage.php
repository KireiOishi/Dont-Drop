<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "jayyr", 3306);

if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in. Please log in first.");
}

// Retrieve user data based on the stored user ID
$userId = $_SESSION['user_id'];

// Perform a query to get user information from the database based on $userId
$query = "SELECT * FROM `user` WHERE `user_id` = $userId";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch user information
    $userData = mysqli_fetch_assoc($result);
    // Close the result set
    mysqli_free_result($result);
} else {
    // Handle the case where the query failed
    die("Query failed: " . mysqli_error($con));
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check which form was submitted
    if (isset($_POST['upload_photo'])) {
        // Handle profile picture upload/change
        // Implement code to handle file upload and update the database
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            // Specify the upload directory
            $uploadDirectory = 'futos/';

            // Generate a unique filename
            $fileName = uniqid('photo_') . '_' . basename($_FILES["profile_picture"]["name"]);

            // Set the complete path for the uploaded file
            $filePath = $uploadDirectory . $fileName;

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
                // Update the user's profile picture in the database
                $updateQuery = "UPDATE user SET profile_picture = '$fileName' WHERE user_id = $userId";

                if (mysqli_query($con, $updateQuery)) {
                    // Success message
                    echo '<script>alert("Profile picture uploaded successfully!");window.location.href = "account_settings.php";</script>';
                } else {
                    // Error message if database update fails
                    echo "Error updating profile picture in the database: " . mysqli_error($con);
                }
            } else {
                // Error message if file upload fails
                echo "Error uploading file.";
            }
        } else {
            // Error message if no file is selected
            echo '<script>alert("Please select a file.")</script>';
        }
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
    <title>Dashboard</title>
   
    
    <style>
   
   body {
    background-color: #f0f0f0;
    color: #333;
    font-family: 'Arial', sans-serif;
}
header {
            background-color: #000;
            color: #fff;
            padding: 10px 120px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
         /* Table Styles */
         table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-left:-270px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        th, td {
            padding: 12px 50px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .logo {
            max-width: 100px; /* Adjust as needed */
        }

        .profile-pic {
            width: 50px; /* Adjust as needed */
            height: 50px; /* Adjust as needed */
            border-radius: 50%;
        }

        nav {
            display: inline-block;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        nav a:hover {
            text-decoration: underline;
        }
        .student-table-container {
    margin: 0 auto; /* Center the container horizontally */
    max-width: 300px; /* Adjust the maximum width as needed */
}

  .close {
    cursor: pointer;
  }
  
  .profile-picture {
            position: relative;
            display: inline-block;
        }

        .profile-picture img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        .dropdown-content a, .dropdown-content form {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover, .dropdown-content input[type="submit"]:hover {
        background-color: #f1f1f1;
    }

    .dropdown-content form {
        margin: 0;
    }

    .dropdown-content input[type="submit"] {
        width: 100%;
        border: none;
        background: none;
        padding: 12px 16px;
        text-align: left;
        cursor: pointer;
        color: black;
        font-size: 14px;
    }

    .dropdown-content input[type="submit"]:hover {
        background-color: #f1f1f1;
    }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown-content.show {
            display: block;
        }
        .content {
            text-align: center; /* Center the heading */
            color: black; /* Set the color to red */
            
        }
        h1{
            color: red;
        }
        th {
          
            color: black; /* Set the color to red */
            
        }
        

       

        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        input[type="email"],
        button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            color: green;
            margin: 20px;
        }
    </style>
   
</head>
<body>
<!-- Navigation Bar -->


<header>
        <div>
            <img src="images/dont.png" alt="Your Logo" class="logo">
        </div>
        <nav>
            <a href="homepage.php">Home</a>
            <a href="about.html">About Us</a>
            <a href="contact.html">Contact Us</a>
            <a href="students.php">Student</a>
            <a href="courses.php">Course</a>
            <a href="subjects.php">Subject</a>
        </nav>
        <div class="profile-picture">
            <img src="futos/<?php echo $userData['profile_picture']; ?>" alt="Profile Picture" onclick="toggleDropdown()">
            <div id="dropdownContent" class="dropdown-content">
                <a href="account_settings.php">Profile Settings</a>
                
                <form action="account_settings.php" method="post">
        <input type="submit" id="logout"  name="logout" value="Logout">
    </form>
            </div>
        </div>
    </header>





<div class="container">
        <div class="dashboard">
            <h2>Dashboard</h2>
           
            <form method="POST" action="send_notification.php">
    <h2>Send Email Notification</h2>
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit" name="sendEmail">Send Alert</button>
        </div>
        <div class="content">
    <h1>MOST AT RISK STUDENTS</h1>
    
    
    
</form>
    <!-- Table container -->
    <div class="student-table-container">
        <table>
            <thead>
                <tr>
                    <th>STUDENT NAME</th>
                    <th>RISK INDEX</th>
                    <th>EMAIL</th>
                    <th>EMAIL ALERT NOTIFICATION</th>
                    <!-- Add more table headers for other student information as needed -->
                </tr>
            </thead>
            <tbody>
            <?php
// PHP code to retrieve and display student records with risk index
// Connect to the database
$con = mysqli_connect("localhost", "root", "", "jayyr", 3306);
if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

// Query to fetch student records and calculate risk index
$query = "SELECT students.*, COUNT(student_activities.id) AS total_activities, 
            SUM(CASE WHEN student_activities.activity_type = 'attendance' AND student_activities.score = 0 THEN 1 ELSE 0 END) AS absences, 
            SUM(CASE WHEN student_activities.score < 50 THEN 1 ELSE 0 END) AS low_scores
          FROM students
          LEFT JOIN student_activities ON students.id = student_activities.student_id
          GROUP BY students.id";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculate risk index
        $total_activities = $row['total_activities'];
        $absences = $row['absences'];
        $low_scores = $row['low_scores'];

        $risk_index = $total_activities > 0 ? ($low_scores / $total_activities) * 0.7 + ($absences / 3) * 0.3 : 0;
        $risk_color = $risk_index > 0.7 ? 'red' : ($risk_index > 0.4 ? 'orange' : 'green');

        echo "<tr>";
        echo "<td>" . $row["student_name"] . "</td>";
        echo "<td>
                <div class='risk-index' style='background-color: $risk_color;'>
                    Risk Index: " . round($risk_index * 100) . "%
                </div>
              </td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>
                <form method='POST' action='send_notification.php'>
                    <input type='hidden' name='email' value='" . $row["email"] . "' />
                    <button type='submit' name='sendEmail'>Send Alert</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No students found</td></tr>";
}

mysqli_close($con);
?>

            </tbody>
        </table>
    </div>
</div>


<!-- 
<footer>  i want the send alert place in the table of student and when click send alert it
     will automatic send via email with the students email
    <div class="footer0">
      <h1>Dont Drop</h1>
    </div>
    <div class="footer1 ">
      Connect with us at<div class="social-media">
        <a href="https://www.facebook.com/jhysnts14/">
          <ion-icon name="logo-facebook"></ion-icon>
        </a>
        <a href="https://www.gmail.com">
          <ion-icon name="logo-google"></ion-icon>
        </a>
       <a href="https://www.youtube.com">
          <ion-icon name="logo-youtube"></ion-icon>
        </a>-->
        <!-- <a href="https://www.instagram.com">
          <ion-icon name="logo-instagram"></ion-icon>
        </a>
        <a href="https://www.twitter.com">
          <ion-icon name="logo-twitter"></ion-icon>
        </a>
      </div>
    </div>
    <div class="footer2">
      <div class="product">
        <div class="heading">Products</div>
        <div class="div">Sell your Products</div>
        <div class="div">Advertise</div>
        <div class="div">Pricing</div>
        <div class="div">Product Buisness</div>

      </div>
      <div class="services">
        <div class="heading">Services</div>
        <div class="div">Return</div>
        <div class="div">Cash Back</div>
        <div class="div">Affiliate Marketing</div>
        <div class="div">Others</div>
      </div>
      <div class="Company">
        <div class="heading">Company</div>
        <div class="div">Complaint</div>
        <div class="div">Careers</div>
        <div class="div">Affiliate Marketing</div>
        <div class="div">Support</div>
      </div>
      <div class="Get Help">
        <div class="heading">Get Help</div>
        <div class="div">Help Center</div>
        <div class="div">Privacy Policy</div>
        <div class="div">Terms</div>
        <div class="div"><a href="signin.html">Login</a></div>
      </div>
    </div>
    <div class="footer3">Copyright © <h4>DontDrop</h4> 2024-2028</div>
  </footer>-->
  <script>
        function toggleDropdown() {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-picture img')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
