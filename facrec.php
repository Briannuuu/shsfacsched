<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);
include('includes/dbcon.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sagad High School Loading System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- External CSS -->
    <link rel="stylesheet" href="css/style - facrec.css">

    <!-- Fonts and Icons -->
    <link rel="shortcut icon" href="img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <!-- DataTables CSS, JS and JQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    
    <!-- Bootstrap Bundle JS (includes Popper for Bootstrap modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<header class="facrec_header">
    <div class="header-logo">
        <img src="img/logo.png" alt="logo" class="logo">
    </div>
    <div class="dropdown">
        <button class="dropbtn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><ion-icon name="log-out-outline"></ion-icon></button>
        <ul class="dropdown-content">
          <a class="dropdown-item" href="login.html">Logout</a>
        </ul>
      </div>
</header>
<body>
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <i class = "bx bxl-netlify"></i>
                <span>Welcome</span>
            </div>
            <i class = "bx bx-menu" id ="btn"></i>
        </div>
        

        <div class="user">
            <div>
                <p>Admin</p>
                <?php
                    $eid=$_SESSION['sid'];
                    $sql="SELECT * from tbladmin where admin_id=:eid ";                                    
                    $query = $dbh -> prepare($sql);
                    $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);

                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                        foreach($results as $row)
                        {    
                        ?>
                        <div class="image">
                            <img class="img-circle"
                            src="staff_images/<?php echo htmlentities($row->u_image);?>" width="90px" height="90px" class="user-image"
                            alt="User profile picture">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"><?php echo ($row->name); ?></a>
                        </div>
                        <?php 
                        }
                    } 
                ?>
            </div>
        </div>

        <ul class="nav-links">
            <li>
                <a href="admin_home.php"><i class = "bx bxs-dashboard"></i><span class="nav-item">Dashboard</span></a>
            </li>
            <li>
                <a href="facloading.php"><i class='bx bx-loader' ></i></i><span class="nav-item">Faculty Loading</span></a>
            </li>
            <li>
                <a href="#" class="current-page"><i class='bx bxs-group' ></i></i><span class="nav-item">Faculty Records</span></a>
            </li>
            <li>
                <a href="settings.php"><i class='bx bx-slider-alt'></i></i><span class="nav-item">Settings</span></a>
            </li>
        </ul>

        <footer>
            <p>All rights Reserved.</p>
        </footer>
    </div>


    <div class="main-content">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Faculty Records</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Manage Faculty Members</h3>    
                                </div>
                                <!--/.card-header -->

                                <div class="card-body mt-2">
                                    <table id="facultyTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th id="column1">#</th>
                                                <th id="column2">Faculty Member Image</th>
                                                <th id="column3">Employee Number</th>
                                                <th id="column4">Name</th>
                                                <th id="column5">Grade Level Handle</th>
                                                <th id="column6">Status</th>
                                                <th id="column7">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="facultyTableBody">
                                            <?php
                                            // Query to fetch faculty data from the database
                                            $query = "SELECT * FROM facmembers";
                                            $result = mysqli_query($con, $query);

                                            // Check if the query failed
                                            if (!$result) {
                                                die("Query failed: " . mysqli_error($con));
                                            }

                                            $cnt = 1;
                                            // Loop through the results and display the faculty data in table rows
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <tr id="facultyRow_<?php echo $row['mem_id']; ?>">
                                                    <!-- Faculty Row -->
                                                    <td id="facultyRowNumber_<?php echo $row['mem_id']; ?>"><?php echo htmlentities($cnt); ?></td>
                                                    <td>
                                                        <a href="#" title="View Profile Image" id="viewProfileLink_<?php echo $row['mem_id']; ?>">
                                                            <!-- Faculty Image -->
                                                            <img src="img/Faculty-Profile/<?php echo htmlentities($row['facImage']); ?>" width="40" height="40" alt="Profile Image" id="facultyImage_<?php echo $row['mem_id']; ?>">
                                                        </a>
                                                    </td>
                                                    <td id="employeeNumber_<?php echo $row['mem_id']; ?>"><?php echo htmlentities($row['emp_no']); ?></td>
                                                    <td id="facultyName_<?php echo $row['mem_id']; ?>"><?php echo htmlentities($row['emp_name']); ?></td>
                                                    <td id="gradeLevel_<?php echo $row['mem_id']; ?>">
                                                        <?php 
                                                        // Check if grade_lvl is assigned, otherwise display 'Not Assigned'
                                                        echo htmlentities($row['grd_lvl'] ?? 'Not Assigned'); 
                                                        ?>
                                                    </td>
                                                    <td id="status_<?php echo $row['mem_id']; ?>"><?php echo htmlentities($row['status']); ?></td>
                                                    <td id="actionButtons_<?php echo $row['mem_id']; ?>">
                                                        <!-- Action buttons for Edit, View, and Delete -->
                                                        <button class="btn btn-primary edit-btn" data-id="<?php echo $row['mem_id']; ?>" title="Edit" id="editBtn_<?php echo $row['mem_id']; ?>">Edit</button>
                                                        <button class="btn btn-success btn-xs edit-btn2" data-id="<?php echo $row['mem_id']; ?>" title="View" id="viewBtn_<?php echo $row['mem_id']; ?>">View</button>
                                                        
                                                        <!-- Delete button with confirmation dialog -->
                                                        <button class="btn btn-danger btn-xs delete-btn" data-id="<?php echo $row['mem_id']; ?>" title="Delete" id="deleteBtn_<?php echo $row['mem_id']; ?>">Delete</button>
                                                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editFacultyModal">Launch demo modal</button> -->
                                                    </td>
                                                </tr>
                                            <?php 
                                                $cnt++; 
                                            } 
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- Modal Structure -->
    <div id="editFacultyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editFacultyModalLabel">
        <div class="modal-dialog modal-xl custom-modal" role="document" id="modalDialog">
            <div class="modal-content" id="modalContentContainer">
                <div class="modal-header" id="modalHeader">
                    <h5 class="modal-title" id="editFacultyModalLabel">Faculty Member Information</h5>
                </div>
                <div id="modalContent" class="modal-body">
                    <!-- Dynamically loaded content -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        let btn = document.querySelector('#btn');
        let sidebar = document.querySelector('.sidebar');
        
        btn.onclick = function (){
            sidebar.classList.toggle('active');
        };
    </script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable for facultyTable
            $('#facultyTable').DataTable({
                "paging": true, // Enable pagination
                "searching": true, // Enable searching
                "ordering": true, // Enable column sorting
                "order": [[ 0, 'asc' ]], // Set default sorting on first column (e.g., employee number)
                "lengthChange": true, // Allow the user to change the number of records per page
                "pageLength": 10, // Set the default number of records per page
            });
        });

        $(document).ready(function() {
            // When the Edit button is clicked
            $(document).on('click', '.edit-btn', function () {
                var edit_id = $(this).data('id');
                console.log("Edit button clicked for Faculty ID: " + edit_id);

                $.ajax({
                    url: 'modal/edit_faculty_member_content.php',
                    type: 'POST',
                    data: { edit_id: edit_id },
                    success: function (response) {
                        console.log(response); // Log the response
                        $('#modalContent').html(response);
                        $('#editFacultyModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Failed to load content. Please try again.');
                    }
                });
            });

            // When the View button is clicked
            $(document).on('click', '.edit-btn2', function () {
                var edit_id = $(this).data('id');
                console.log("View button clicked for Faculty ID: " + edit_id);

                $.ajax({
                    url: 'modal/view_faculty_member_content.php',
                    type: 'POST',
                    data: { edit_id: edit_id },
                    success: function (response) {
                        console.log(response);
                        $('#modalContent').html(response);
                        $('#editFacultyModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Failed to load content. Please try again.');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function () {
                var delete_id = $(this).data('id'); // Fetch the ID from the button
                console.log("Delete button clicked for Faculty ID: " + delete_id); // Log the delete click

                if (confirm("Are you sure you want to delete this faculty member?")) {
                    $.ajax({
                        url: 'php/delete_faculty.php', // PHP script to handle delete
                        type: 'POST',
                        data: { delete_id: delete_id },
                        dataType: 'json', // Expect JSON response
                        success: function (response) {
                            if (response.status === "success") {
                                console.log("Deleted faculty member ID: " + delete_id);
                                $('#facultyRow_' + delete_id).remove(); // Remove row from table
                            } else {
                                console.error("Server Error: " + response.message);
                                alert('Failed to delete faculty member. Reason: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error: " + error);
                            console.error("Response: " + xhr.responseText);
                            alert('Failed to delete faculty member. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
