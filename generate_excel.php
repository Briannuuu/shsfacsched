<?php
// Include DB connection
include('includes/dbcon.php');

// Get the instructor from the query parameter
$instructor = $_GET['instructor'] ?? '';

if (!$instructor) {
    die("Instructor not specified.");
}

// Fetch schedule data based on the selected instructor
$query = $dbh->prepare("SELECT * FROM schedules WHERE fac_name = :instructor");
$query->bindParam(':instructor', $instructor, PDO::PARAM_STR);
$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$data) {
    die("No data found for the selected instructor.");
}

// Set the filename for the CSV
$filename = 'schedule_report.csv';

// Set the headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Open PHP output stream for writing the CSV
$output = fopen('php://output', 'w');

// Set the header row
fputcsv($output, ['Subject', 'Room', 'Section', 'Day', 'Start Time', 'End Time']);

// Add data to the CSV file
foreach ($data as $row) {
    fputcsv($output, [
        $row['subj_name'],
        $row['room_name'] . ' ' . $row['room_no'],
        $row['sec_name'],
        $row['day'],
        $row['start_time'],
        $row['end_time']
    ]);
}

// Close the CSV output
fclose($output);
exit;
?>