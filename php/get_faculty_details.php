<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection file
require_once '../include/dbconn.php';  // Adjust the path to your database connection file

// Check if 'id' is passed in the GET request
if (isset($_GET['id'])) {
    $mem_id = $_GET['id'];  // Get the 'id' value from the AJAX request

    // Prepare SQL query to fetch faculty details based on the provided 'id'
    $sql = "SELECT * FROM facmembers WHERE mem_id = ?";
    
    // Prepare statement
    if ($stmt = $con->prepare($sql)) {
        // Bind the 'id' parameter to the SQL query
        $stmt->bind_param("i", $mem_id); // "i" indicates integer data type
        
        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Fetch the details of the faculty member
                $faculty = $result->fetch_assoc();
                
                // You can output the details as HTML here (for example)
                echo "<form>";
                echo "<label for='faculty_name'>Name:</label><input type='text' id='faculty_name' value='" . $faculty['name'] . "'><br>";
                echo "<label for='faculty_email'>Email:</label><input type='email' id='faculty_email' value='" . $faculty['email'] . "'><br>";
                // Add more fields as needed
                echo "</form>";
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
    echo "No ID parameter provided.";
}
?>
