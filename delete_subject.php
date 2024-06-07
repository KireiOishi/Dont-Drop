<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $con->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: subjects.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
