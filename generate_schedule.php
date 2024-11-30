<?php
include('includes/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instructor'])) {
    $instructor = trim($_POST['instructor']);
    error_log("Received instructor ID: " . $instructor);
    
    // Validate instructor input
    if (empty($instructor)) {
        echo "<tr><td colspan='6'>Invalid instructor ID.</td></tr>";
        exit;
    }
    
    // Call the Python script
    $instructorData = ["instructor" => $instructor];
    $command = "python py/generate_schedule.py " . escapeshellarg(json_encode($instructorData));
    $output = shell_exec($command);

    // Log the output
    file_put_contents('generate_schedule_output.log', $output, FILE_APPEND);

    // Output the result
    echo $output;
} else {
    echo "<tr><td colspan='6'>Invalid request method or missing parameters.</td></tr>";
}
?>
