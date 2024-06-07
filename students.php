<?php
include 'db.php';

// Retrieve unique courses from the database
$coursesResult = $con->query("SELECT DISTINCT course_name FROM courses");

// Fetch course names into an array
$courses = [];
while ($row = $coursesResult->fetch_assoc()) {
    $courses[] = $row['course_name'];
}

// Initialize variables
$selectedCourse = isset($_GET['course']) ? $_GET['course'] : '';
$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'student_name';
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC';

// Base query
$query = "SELECT students.*, courses.course_name FROM students LEFT JOIN courses ON students.course_id = courses.id";

// Apply course filter if selected
if (!empty($selectedCourse)) {
    $query .= " WHERE courses.course_name = '$selectedCourse'";
}

// Apply sorting
$query .= " ORDER BY students.$sortField $sortOrder";

// Execute query
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 20px;
        }
        a {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
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
        .actions a.s {
            margin-right: 10px;
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .actions a:hover {
            background-color: #218838;
        }
        .actions a.delete {
            background-color: #dc3545;
        }
        .actions a.delete:hover {
            background-color: #c82333;
        }
        header {
            background-color: #000;
            color: #fff;
            padding: 10px 120px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }.profile-picture {
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
        form {
            display: flex;
            align-items: center;
        }

        select, button {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        select:focus, button:focus {
            border-color: #007bff;
        }

        button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
        <div>
            <img src="images/dont.png" alt="Your Logo" class="logo">
        </div>
        <nav>
            <a href="homepage.php">Home</a>
            <a href="about.html">About Us</a>
            <a href="contact.html">Contact Us</a>
            <a href="students.php">Student</a>
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
        <h2>STUDENTS LIST</h2>
        <form method="GET" action="students.php">
            <label for="course">Filter by Course:</label>
            <select name="course" id="course" onchange="this.form.submit()">
                <option value="">All Courses</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo urlencode($course); ?>" <?php if ($selectedCourse == $course) echo 'selected'; ?>><?php echo $course; ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="sortField">Sort by:</label>
            <select name="sortField" id="sortField" onchange="this.form.submit()">
                <option value="student_name" <?php if ($sortField == 'student_name') echo 'selected'; ?>>Student Name</option>
                <option value="email" <?php if ($sortField == 'email') echo 'selected'; ?>>Email</option>
                
            </select>

            <label for="sortOrder">Order:</label>
            <select name="sortOrder" id="sortOrder" onchange="this.form.submit()">
                <option value="ASC" <?php if ($sortOrder == 'ASC') echo 'selected'; ?>>Ascending</option>
                <option value="DESC" <?php if ($sortOrder == 'DESC') echo 'selected'; ?>>Descending</option>
            </select>
        </form>

        <a class="s" href="add_student.php">Add New Student</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['course_name'] ?></td>
                    <td class="actions">
                        <a href="edit_student.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="delete_student.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                        <a href="student_activities.php?student_id=<?php echo $row['id']; ?>">Records</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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
