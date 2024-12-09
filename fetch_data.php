<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/dbcon.php');
header('Content-Type: application/json');

// Check if 'type' is set in GET parameters
$type = $_GET['type'] ?? '';

try {
    switch ($type) {
        case 'subjects':
            $query = "SELECT subj_id AS id, subj_name AS name FROM subjects";
            break;
        case 'rooms':
            $query = "SELECT room_id AS id, room_no AS name FROM room";
            break;
        case 'sections':
            $query = "SELECT lvlsec_id AS id, grade_sec AS name FROM lvlsec";
            break;
        default:
            echo json_encode(['error' => 'Invalid type parameter']);
            exit;
    }

    // Execute the query and fetch results
    $stmt = $dbh->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if data is valid before returning
    if (!empty($data)) {
        echo json_encode($data);  // Send the array as a JSON response
    } else {
        echo json_encode(['error' => 'No data found']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>