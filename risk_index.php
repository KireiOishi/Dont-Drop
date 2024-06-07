<?php
// Calculate risk index (this part of the code remains the same)
$low_score_threshold = 50;
$absent_threshold = 3;
$total_activities = 0;
$low_scores = 0;
$absences = 0;

while ($row = $result->fetch_assoc()) {
    $total_activities++;
    if ($row['activity_type'] == 'attendance' && $row['score'] == 0) {
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
?>

<tr>
    <td colspan="4" style="text-align: right; font-weight: bold;">Risk Index:</td>
    <td style="background-color: <?php echo $risk_color; ?>; color: white;"><?php echo round($risk_index * 100) . '%'; ?></td>
</tr>
