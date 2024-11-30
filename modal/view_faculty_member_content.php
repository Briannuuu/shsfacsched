<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../includes/dbcon.php');

$faculty_data = null;

if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    //echo "Received Member ID: " . htmlspecialchars($edit_id); // Debugging

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
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
        <div class="section-container">
            <header class="section-header">Personal Information</header>
            <div class="row">
                <div class="col-md-3 left-part p-3 text-center">
                    <img id="profileImage" src="img/Faculty-Profile/<?php echo htmlspecialchars($faculty_data['facImage']); ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-5" style="width: auto; height: 150px; max-width: 100%; object-fit: cover;">                    
                    <h4><strong><?php echo htmlspecialchars($faculty_data['emp_name']); ?></strong></h4>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($faculty_data['status']); ?></p>
                    <p><strong>Role:</strong> Faculty Member</p>
                </div>
                <div class="col-md-9 right-part p-3">
                    <p><strong>Member ID:</strong> <?php echo htmlspecialchars($faculty_data['mem_id']); ?></p>
                    <p><strong>Employee Number:</strong> <?php echo htmlspecialchars($faculty_data['emp_no']); ?></p>
                    <p><strong>Birthday:</strong> <?php echo htmlspecialchars($faculty_data['birthday']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($faculty_data['age']); ?> years old</p>
                    <p><strong>Sex:</strong> <?php echo htmlspecialchars($faculty_data['sex']); ?></p>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="section-container">
            <header class="section-header">Contact Information</header>
            <div class="row">
                <div class="col-md-6 left-part p-3">
                    <strong>Email Address:</strong>
                    <p><?php echo htmlspecialchars($faculty_data['email']); ?></p>
                </div>
                <div class="col-md-6 right-part p-3">
                    <strong>Contact Number:</strong>
                    <p><?php echo htmlspecialchars($faculty_data['cp']); ?></p>
                </div>
            </div>
        </div>

        <!-- Address Section -->
        <div class="section-container">
            <header class="section-header">Address</header>
            <div class="row">
                <div class="col-md-6 left-part p-3">
                    <strong>Street Address:</strong>
                    <p><?php echo htmlspecialchars($faculty_data['address']); ?></p>
                    <strong>Barangay:</strong>
                    <p><?php echo htmlspecialchars($faculty_data['barangay']); ?></p>
                </div>
                <div class="col-md-6 right-part p-3">
                    <strong>City:</strong>
                    <p><?php echo htmlspecialchars($faculty_data['province']); ?></p>
                    <strong>Province:</strong>
                    <p><?php echo htmlspecialchars($faculty_data['province']); ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Close Button -->
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" id="closeModalButton" data-bs-dismiss="modal">Close</button>
    </div>
</body>
</html>

<script>
    // Close Button functionality
    $('#closeModalButton').click(function() {
        $('#editFacultyModal').modal('hide');
    });
</script>