<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../includes/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mem_id']) && !empty($_POST['mem_id'])) {
        $mem_id = intval($_POST['mem_id']); // Sanitize the member ID

        // Capture all the fields
        $emp_no = isset($_POST['emp_no']) ? trim($_POST['emp_no']) : null; // Employee number
        $emp_name = isset($_POST['emp_name']) ? trim($_POST['emp_name']) : null;
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;
        $birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : null;
        $age = isset($_POST['age']) ? intval($_POST['age']) : null;
        $sex = isset($_POST['sex']) ? trim($_POST['sex']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $cp = isset($_POST['cp']) ? trim($_POST['cp']) : null;
        $address = isset($_POST['address']) ? trim($_POST['address']) : null;
        $barangay = isset($_POST['barangay']) ? trim($_POST['barangay']) : null;
        $city = isset($_POST['city']) ? trim($_POST['city']) : null;
        $province = isset($_POST['province']) ? trim($_POST['province']) : null;

        // Initialize variables for the query
        $set_values = [];
        $params = [];
        $types = '';

        // Calculate age from the birthday if it's available
        if ($birthday) {
            $birthDate = new DateTime($birthday);
            $today = new DateTime();
            $calculatedAge = $today->diff($birthDate)->y;
            // Use the calculated age if it's not provided
            $newAge = $age ?: $calculatedAge;
        }

        // Handle profile image upload
        $imageName = $_FILES['profile_image']['name'];
        $imageTmpName = $_FILES['profile_image']['tmp_name'];
        $imageError = $_FILES['profile_image']['error'];

        // Add fields to be updated dynamically
        if ($emp_name) {
            $set_values[] = "emp_name = ?";
            $types .= 's';
            $params[] = $emp_name;
        }
        if ($status) {
            $set_values[] = "status = ?";
            $types .= 's';
            $params[] = $status;
        }
        if ($birthday) {
            $set_values[] = "birthday = ?";
            $types .= 's';
            $params[] = $birthday;
        }
        if ($newAge !== null) {
            $set_values[] = "age = ?";
            $types .= 'i'; // 'i' for integer
            $params[] = $newAge;
        }
        if ($sex) {
            $set_values[] = "sex = ?";
            $types .= 's';
            $params[] = $sex;
        }
        if ($email) {
            $set_values[] = "email = ?";
            $types .= 's';
            $params[] = $email;
        }
        if ($cp) {
            $set_values[] = "cp = ?";
            $types .= 's';
            $params[] = $cp;
        }
        if ($address) {
            $set_values[] = "address = ?";
            $types .= 's';
            $params[] = $address;
        }
        if ($barangay) {
            $set_values[] = "barangay = ?";
            $types .= 's';
            $params[] = $barangay;
        }
        if ($province) {
            $set_values[] = "province = ?";
            $types .= 's';
            $params[] = $province;
        }

        // If a new image is uploaded, handle the file upload
        if ($imageError === 0) {
            // Validate image type
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $imageExtension = strtolower($imageExtension);
            $allowed = array('jpg', 'jpeg', 'png');

            // Check if the file extension is allowed
            if (in_array($imageExtension, $allowed)) {
                // Create the new image name: emp_no-emp_name.extension
                if ($emp_no && $emp_name) {
                    // Replace spaces in emp_name with dashes
                    $newImageName = $emp_no . '-' . preg_replace('/\s+/', '-', $emp_name) . "." . $imageExtension;
                }
                // Define the destination path
                // Always change to hardcore diretory when local - IMPORTANT
                $imageDestination = "C:/xampp/htdocs/SAGADHS-FLS/img/Faculty-Profile/" . $newImageName;

                // Move the uploaded file to the destination
                
                if (move_uploaded_file($imageTmpName, $imageDestination)) {
                    // Image uploaded successfully
                    $set_values[] = "facImage = ?";
                    $types .= 's'; // 's' for string
                    $params[] = $newImageName;
                } else {
                    die("Error moving the uploaded image.");
                }
            } else {
                die("Invalid file type. Allowed types: jpg, jpeg, png, gif.");
            }
        }

        // If no fields to update, stop execution
        if (empty($set_values)) {
            die("No fields provided for update.");
        }

        // Construct the SQL query
        $query = "UPDATE facmembers SET " . implode(", ", $set_values) . " WHERE mem_id = ?";
        $params[] = $mem_id;
        $types .= 'i'; // 'i' for integer

        // Prepare the statement
        $stmt = $con->prepare($query);

        if ($stmt) {
            // Bind parameters dynamically
            $stmt->bind_param($types, ...$params);

            // Execute the query
            if ($stmt->execute()) {
                header("Location: ../facrec.php?update=success");
                exit;
            } else {
                die("Error executing query: " . $stmt->error);
            }
        } else {
            die("Error preparing statement: " . $con->error);
        }
    } else {
        die("Invalid request: Faculty member ID is missing.");
    }
} else {
    die("Invalid request method.");
}
?>