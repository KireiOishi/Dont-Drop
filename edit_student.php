<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $con->query("SELECT * FROM students WHERE id = $id");
    $student = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $course_id = $_POST['course_id'];

    $stmt = $con->prepare("UPDATE students SET student_name = ?, email = ?, course_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $student_name, $email, $course_id, $id);

    if ($stmt->execute()) {
        header('Location: students.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$courses = $con->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>body {
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
input[type="email"],
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
input[type="email"]:focus,
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
<a href="students.php" style="position: absolute; top: 20px; left: 40px; text-decoration: none; color: black;">
    <svg width="54" height="74" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Outer circle -->
        <circle cx="12" cy="12" r="10" fill="#F7F7F7" stroke="black" stroke-width="2"/>
        <!-- Inner arrow shape -->
        <path d="M8 12H16M8 12L12 8M8 12L12 16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</a>
    <h2>Edit Student</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">
        <label>Student Name:</label><br>
        <input type="text" name="student_name" value="<?= $student['student_name'] ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $student['email'] ?>" required><br>
        <label>Course:</label><br>
        <select name="course_id" required>
            <?php while($row = $courses->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $student['course_id'] ? 'selected' : '' ?>><?= $row['course_name'] ?></option>
            <?php endwhile; ?>
        </select><br>
        <button type="submit">Update Student</button>
    </form>
</body>
</html>
