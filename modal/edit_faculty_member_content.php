<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/dbcon.php';

$faculty_data = null;

if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    // echo "Received Member ID: " . htmlspecialchars($edit_id); // Debugging

    $query = "SELECT * FROM facmembers WHERE mem_id = ?";
    if ($stmt = mysqli_prepare($con, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $edit_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $faculty_data = mysqli_fetch_assoc($result);
            if (!$faculty_data) {
                echo "No data found for the given ID.";
            }
        } else {
            echo "Execution failed: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Statement preparation failed: " . mysqli_error($con);
    }
} else {
    echo "edit_id is not set.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Faculty Member Details</title>
    <style>
        .section-container {
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .section-header {
            font-weight: bold;
            font-size: 1.25rem;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Personal Information Section -->
        <form action="php/update_faculty_member.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden input for Member ID -->
            <input type="hidden" name="mem_id" value="<?php echo htmlspecialchars($faculty_data['mem_id']); ?>" />
            <!-- Hidden input for Employee Number (emp_no) -->
            <input type="hidden" name="emp_no" value="<?php echo htmlspecialchars($faculty_data['emp_no']); ?>" />
            <!-- Hidden input for Employee Name (emp_name) -->
            <input type="hidden" name="emp_name" value="<?php echo htmlspecialchars($faculty_data['emp_name']); ?>" />

            <div class="section-container">
                <header class="section-header">Personal Information</header>
                <div class="row">
                    <div class="col-md-3 left-part p-3 text-center">
                        <!-- Display current image or default image if not available -->
                        <img id="profileImage" src="img/Faculty-Profile/<?php echo htmlspecialchars($faculty_data['facImage']); ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-5" style="width: auto; height: 150px; max-width: 100%; object-fit: cover;">
                        <h4><strong><?php echo htmlspecialchars($faculty_data['emp_name']); ?></strong></h4>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($faculty_data['status']); ?></p>
                        <p><strong>Role:</strong> Faculty Member</p>

                        <!-- Image Upload Button -->
                        <label for="imageUpload" class="btn btn-outline-primary">Upload new photo</label>
                        <input type="file" id="imageUpload" name="profile_image" class="d-none" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="col-md-9 right-part p-3">
                        <p><strong>Member ID:</strong> <?php echo htmlspecialchars($faculty_data['mem_id']); ?></p>
                        <p><strong>Employee Number:</strong> <?php echo htmlspecialchars($faculty_data['emp_no']); ?></p>
                        <div class="mb-3">
                            <strong>Birthday:</strong>
                            <input type="date" class="form-control" name="birthday" value="<?php echo htmlspecialchars($faculty_data['birthday']); ?>" />
                        </div>
                        <div class="mb-3">
                            <strong>Age:</strong>
                            <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($faculty_data['age']); ?>" />
                        </div>
                        <div class="mb-3">
                            <strong>Sex:</strong>
                            <select class="form-control" name="sex">
                                <option value="Male" <?php echo $faculty_data['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo $faculty_data['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo $faculty_data['sex'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="section-container">
                <header class="section-header">Contact Information</header>
                <div class="row">
                    <div class="col-md-6 left-part p-3">
                        <strong>Email Address:</strong>
                        <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($faculty_data['email']); ?>" />
                    </div>
                    <div class="col-md-6 right-part p-3">
                        <div class="mb-3">
                            <strong>Contact Number:</strong>
                            <input type="text" class="form-control" name="cp" value="<?php echo htmlspecialchars($faculty_data['cp']); ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="section-container">
                <header class="section-header">Address</header>
                <div class="row">
                    <div class="col-md-6 left-part p-3">
                        <div class="mb-3">
                            <strong>Street Address:</strong>
                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($faculty_data['address']); ?>" />
                        </div>
                        <div class="mb-3">
                            <strong>Barangay:</strong>
                            <input type="text" class="form-control" name="barangay" value="<?php echo htmlspecialchars($faculty_data['barangay']); ?>" />
                        </div>
                    </div>
                    <div class="col-md-6 right-part p-3">
                        <div class="mb-3">
                            <strong>City:</strong>
                            <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($faculty_data['province']); ?>" />
                        </div>
                        <div class="mb-3">
                            <strong>Province:</strong>
                            <input type="text" class="form-control" name="province" value="<?php echo htmlspecialchars($faculty_data['province']); ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit and Close buttons -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-secondary" id="closeModalButton" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];  // Get the selected file

            // Debugging: Check if a file was selected
            if (!file) {
                console.log("No file selected.");
                return;  // Exit the function if no file is selected
            }

            // Check if the file is an image
            if (!file.type.startsWith('image/')) {
                console.log("The selected file is not an image.");
                return;  // Exit if the file is not an image
            }

            const reader = new FileReader();

            reader.onload = function() {
                const output = document.getElementById('profileImage');
                output.src = reader.result;  // Update the image source to the selected file
            };

            reader.onerror = function(error) {
                console.error("Error reading the file: ", error);
            };

            reader.readAsDataURL(file);  // Read the file as a data URL
        }
    </script>
</body>
</html>