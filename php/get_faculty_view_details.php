<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
require_once '../include/dbconn.php';
session_start();

// Check if 'id' is passed in the GET request
if (isset($_GET['id'])) {
    $faculty_id = $_GET['id'];  // Get the faculty ID from the GET request
    error_log("PHP: View Faculty ID: " . $faculty_id); // Log the received ID

    // Prepare SQL query to fetch faculty details based on the provided 'id'
    $sql = "SELECT * FROM facmembers WHERE mem_id = ?";

    // Prepare the statement
    if ($stmt = $con->prepare($sql)) {
        // Bind the 'id' parameter to the SQL query
        $stmt->bind_param("i", $faculty_id); // "i" indicates integer data type
        
        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Fetch the faculty details
                $faculty = $result->fetch_assoc();
                
                // Log the fetched data (sensitive info might be omitted in production)
                error_log("PHP: Faculty Details: " . json_encode($faculty)); 

                // Output the faculty details for viewing (no editable fields)
                echo "<p><strong>Name:</strong> " . htmlspecialchars($faculty['name']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($faculty['email']) . "</p>";
                // Add more fields as necessary
            } else {
                echo "No faculty member found with this ID.";
            }
        } else {
            echo "Error executing query.";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL query.";
    }

    // Close the database connection
    $con->close();
} else {
    error_log("PHP: No Faculty ID provided.");
    echo "Invalid request.";
}
?>
