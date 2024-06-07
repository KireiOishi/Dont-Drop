<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $con->query("SELECT * FROM courses WHERE id = $id");
    $course = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $stmt = $con->prepare("UPDATE courses SET course_name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $course_name, $description, $id);

    if ($stmt->execute()) {
        header('Location: courses.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
}

form {
    max-width: 400px;
    margin: 0 auto;
}

label {
    font-weight: bold;
}

input[type="text"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button {
    background-color: #4caf50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color: #45a049;
}

input[type="text"]:focus,
select:focus {
    border-color: #2ecc71;
    outline: none;
}

.error {
    color: red;
    font-size: 14px;
}

    </style>
</head>
<body>
<a href="courses.php" style="position: absolute; top: 20px; left: 40px; text-decoration: none; color: black;">
    <svg width="54" height="74" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Outer circle -->
        <circle cx="12" cy="12" r="10" fill="#F7F7F7" stroke="black" stroke-width="2"/>
        <!-- Inner arrow shape -->
        <path d="M8 12H16M8 12L12 8M8 12L12 16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</a>
    <h2>Edit Course</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= $course['id'] ?>">
        <label>Course Name:</label><br>
        <input type="text" name="course_name" value="<?= $course['course_name'] ?>" required><br>
        <label>Description:</label><br>
        <textarea name="description" required><?= $course['description'] ?></textarea><br>
        <button type="submit">Update Course</button>
    </form>
</body>
</html>
