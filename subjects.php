<?php
include 'db.php';

$result = $con->query("SELECT subjects.*, courses.course_name FROM subjects LEFT JOIN courses ON subjects.course_id = courses.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subjects</title>
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
        .b {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .b:hover {
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
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .edit-link, .delete-link {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .edit-link {
            background-color: #28a745;
            color: #fff;
        }
        .edit-link:hover {
            background-color: #218838;
        }
        .delete-link {
            background-color: #dc3545;
            color: #fff;
        }
        .delete-link:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<a href="homepage.php" style="position: absolute; top: 0px; left: 20px; text-decoration: none; color: black;">
    <svg width="54" height="74" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Outer circle -->
        <circle cx="12" cy="12" r="10" fill="#F7F7F7" stroke="black" stroke-width="2"/>
        <!-- Inner arrow shape -->
        <path d="M8 12H16M8 12L12 8M8 12L12 16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</a>
    <h2>Subjects</h2>
    <a class="b" href="add_subject.php">Add New Subject</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Subject Name</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['subject_name'] ?></td>
            <td><?= $row['course_name'] ?></td>
            <td>
                <a href="edit_subject.php?id=<?= $row['id'] ?>"class="edit-link">Edit</a>
                <a href="delete_subject.php?id=<?= $row['id'] ?>" class="delete-link"onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
