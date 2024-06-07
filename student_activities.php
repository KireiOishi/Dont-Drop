<?php
include 'db.php';

if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    die('Error: Missing student ID.');
}

$student_id = $_GET['student_id'];

// Fetch student information
$student_result = $con->query("SELECT student_name FROM students WHERE id=$student_id");
if ($student_result->num_rows == 0) {
    die('Error: Student not found.');
}
$student = $student_result->fetch_assoc();
$student_name = $student['student_name'];

// Add or Update activity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_activity'])) {
    $activity_id = $_POST['activity_id'] ?? null;
    $activity_type = $_POST['activity_type'];
    $score = $_POST['score'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $datetime = $date . ' ' . $time;

    if ($activity_id) {
        // Update existing activity
        $query = "UPDATE student_activities SET activity_type='$activity_type', score='$score', datetime='$datetime' WHERE id='$activity_id' AND student_id='$student_id'";
    } else {
        // Insert new activity
        $query = "INSERT INTO student_activities (student_id, activity_type, score, datetime) VALUES ('$student_id', '$activity_type', '$score', '$datetime')";
    }
    $con->query($query);
}

// Delete multiple activities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_selected'])) {
    if (isset($_POST['selected_activities'])) {
        $selected_activities = $_POST['selected_activities'];
        $activity_ids = implode(',', array_map('intval', $selected_activities));
        $query = "DELETE FROM student_activities WHERE id IN ($activity_ids) AND student_id='$student_id'";
        $con->query($query);
    }
}

// Fetch activities with applied filter
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'all';
$filter_condition = '';

if ($filter_type !== 'all') {
    // Filter condition based on the selected filter type
    $filter_condition = " AND activity_type='$filter_type'";
}

$result = $con->query("SELECT * FROM student_activities WHERE student_id=$student_id $filter_condition");

// Calculate risk index
$low_score_threshold = 50;
$absent_threshold = 3;
$total_activities = 0;
$low_scores = 0;
$absences = 0;

while ($row = $result->fetch_assoc()) {
    $total_activities++;
    if ($row['activity_type'] == 'attendance' && $row['score'] == 1) {
        $absences++;
    } elseif ($row['score'] < $low_score_threshold) {
        $low_scores++;
    }
}

if ($total_activities > 0) {
    $risk_index = ($low_scores / $total_activities) * 0.7 + ($absences / $absent_threshold) * 0.3;
    $risk_color = $risk_index > 0.7 ? 'red' : ($risk_index > 0.4 ? 'orange' : 'green');
} else {
    $risk_index = 0;
    $risk_color = 'green';
}

$result->data_seek(0); // Reset the result set pointer for table display
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Activities</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #333;
            margin: 20px 0;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            max-width: 500px;
        }

        select, input[type="number"], input[type="date"], input[type="time"], button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
        .delete {
            padding: 5px 10px;
            font-size: 14px;
            margin: 10px 10px 10px 0;
            max-width: 10%;
        }
        .filter {
            padding: 5px 10px;
            font-size: 14px;
            margin: 10px 10px 10px 0;
            max-width: 10%;
        }
        .print {
            padding: 5px 10px;
            font-size: 14px;
            margin: 10px 10px 10px 0;
            max-width: 10%;
        }
        .delete-button {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            
        }

        .delete-button:hover {
            background-color: #c82333;
        }


        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f9;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .edit-form {
            display: none;
        }

        .risk-index {
            padding: 10px;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
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
    <h1>Student Records</h1>
    <form id="activity-form" method="POST" action="student_activities.php?student_id=<?php echo $student_id; ?>">
        <input type="hidden" name="activity_id" id="activity_id">
        <select name="activity_type" id="activity_type" required>
            <option value="quiz">Quiz</option>
            <option value="activity">Activity</option>
            <option value="exam">Exam</option>
            <option value="attendance">Attendance</option>
        </select>
        <input type="number" name="score" id="score" placeholder="Score" required>
        <input type="date" name="date" id="date" required>
        <input type="time" name="time" id="time" required>
        <button type="submit" name="save_activity">Save Record</button>
    </form>
    
    <table>
    <form id="filter-form" method="GET" action="student_activities.php">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        <select name="filter_type" id="filter_type">
            <option value="all">All Records</option>
            <option value="quiz">Quiz</option>
            <option value="activity">Activity</option>
            <option value="exam">Exam</option>
            <option value="attendance">Attendance</option>
        </select>
        <button type="submit" class="filter">Filter</button>
        <button type="submit" class="print" name="print_pdf">PRINT PDF</button>
    </form>
    
    <form id="delete-form" method="POST" action="student_activities.php?student_id=<?php echo $student_id; ?>">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        <tr>
            <th>Select</th>
            <th>ID</th>
            <th>Activity Type</th>
            <th>Score</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><input type="checkbox" name="selected_activities[]" value="<?php echo $row['id']; ?>"></td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['activity_type']; ?></td>
            <td><?php echo $row['score']; ?></td>
            <td><?php echo $row['datetime']; ?></td>
            <td>
                <button type="button" onclick="editActivity(<?php echo $row['id']; ?>, '<?php echo $row['activity_type']; ?>', <?php echo $row['score']; ?>, '<?php echo $row['datetime']; ?>')">Edit</button>
                <a href="student_activities.php?student_id=<?php echo $student_id; ?>&delete=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <button type="submit" name="delete_selected" class="delete" onclick="return confirm('Are you sure you want to delete selected records?')">Delete Selected</button>
    </form>

    <div class="risk-index" style="background-color: <?php echo $risk_color; ?>">
        Risk Index: <?php echo round($risk_index * 100); ?>%
    </div>

    <script>
        function editActivity(id, activityType, score, date) {
            document.getElementById('activity_id').value = id;
            document.getElementById('activity_type').value = activityType;
            document.getElementById('score').value = score;
            document.getElementById('date').value = date;
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
