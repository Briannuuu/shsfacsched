<?php
// Include the database connection file
include('../includes/dbcon.php');

// Check if 'delete_id' is set in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $mem_id = $_POST['delete_id']; // Get the member ID from the POST request

    // Sanitize the member ID to prevent SQL injection
    $mem_id = mysqli_real_escape_string($con, $mem_id);

    // MySQL query to delete the faculty member based on 'mem_id'
    $query = "DELETE FROM facmembers WHERE mem_id = '$mem_id'";

    // Execute the query
    if (mysqli_query($con, $query)) {
        // If the query is successful, send a JSON response
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
        
    } else {
        // If the query fails, send an error response
        echo json_encode(["status" => "error", "message" => "Error deleting record: " . mysqli_error($con)]);
    }
} else {
    // If 'delete_id' is not set or request is not POST, send an error response
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
