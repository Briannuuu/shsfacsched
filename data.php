<?php
session_start();
error_reporting(0);
include('includes/dbcon.php');
if (strlen($_SESSION['sid'] == 0)) {
    header("X-Content-Type-Options: nosniff");
} 
if (isset($_GET['del'])) {
    mysqli_query($con, "DELETE FROM facmembers WHERE mem_id = '" . $_GET['mem_id'] . "'");
    $_SESSION['delmsg'] = "Faculty Member Deleted Successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sagad High School Loading System</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/adminlte.css">
    <link rel="stylesheet" href="css/facres.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Main Header -->
    <header class="main_header">
        <div class="header-overlay"></div>
        <div class="header-logo">
            <img src="img/logo.png" alt="logo" class="logo">
        </div>
        <div class="dropdown">
            <button class="dropbtn" type="button" title="Logout">
                <ion-icon name="log-out-outline"></ion-icon>
            </button>
            <!-- Logout Dropdown -->
            <ul class="dropdown-content">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <i class="bx bxl-netlify"></i>
                <span>Welcome</span>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <div class="user">
            <div>
                <p>Admin</p>
                <?php
                    $eid = $_SESSION['sid'];
                    $sql = "SELECT * FROM tbladmin WHERE admin_id = :eid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                ?>
                <div class="image">
                    <img class="img-circle" src="staff_images/<?php echo htmlentities($row->u_image); ?>" width="90" height="90" alt="User profile picture">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?php echo htmlentities($row->name); ?></a>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <ul class="nav-links">
        <li><a href="#" class="current-page"><i class="bx bxs-dashboard"></i><span class="nav-item">Dashboard</span></a></li>
        <li><a href="facloading.html"><i class="bx bx-loader"></i><span class="nav-item">Faculty Loading</span></a></li>
        <li><a href="facrec.php"><i class="bx bxs-group"></i><span class="nav-item">Faculty Records</span></a></li>
        <li><a href="facsettings.html"><i class="bx bx-slider-alt"></i><span class="nav-item">Settings</span></a></li>
    </ul>

    <footer>
        <p>All rights Reserved.</p>
    </footer>

    <div class="main-content">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Faculty Member Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Manage Faculty Members</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Manage Faculty Members</h3>
                                </div>
                                <div class="card-body mt-2">
                                    <table id="facultyTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Faculty Member Image</th>
                                                <th>Employee Number</th>
                                                <th>Name</th>
                                                <th>Grade Level Handle</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM facmembers";
                                            $result = mysqli_query($con, $query);
                                            $cnt = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td>
                                                        <a href="#" title="View Profile Image">
                                                            <img src="facimages/<?php echo htmlentities($row['facImage']); ?>" width="40" height="40" alt="Profile Image">
                                                        </a>
                                                    </td>
                                                    <td><?php echo htmlentities($row['emp_no']); ?></td>
                                                    <td><?php echo htmlentities($row['emp_name']); ?></td>
                                                    <td><?php echo htmlentities($row['grade_lvl']); ?></td>
                                                    <td><?php echo htmlentities($row['status']); ?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-xs edit_data" id="<?php echo $row['mem_id']; ?>" title="Edit">Edit</button>
                                                        <button class="btn btn-success btn-xs edit_data2" id="<?php echo $row['mem_id']; ?>" title="View">View</button>
                                                        <a href="facrec.php?id=<?php echo $row['mem_id'] ?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-danger btn-xs">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#facultyTable').DataTable({
                "responsive": true,
                "autoWidth": false
            });
        });
    </script>
</body>
</html>
