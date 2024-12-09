<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/dbcon.php');

if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
    exit();
} else {
    if (isset($_POST['submit'])) {
        $emp_no = $_POST['emp_no'];
        $emp_name = $_POST['emp_name'];
        $birthday = $_POST['birthday'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];
        $email = $_POST['email'];
        $cp = $_POST['cp'];
        $grd_lvl = $_POST['grd_lvl'];
        $status = $_POST['status'];
        $barangay = $_POST['barangay'];
        $district = $_POST['district'];
        $province = $_POST['province'];
        $address = $_POST['address'];
        $permission = $_POST['permission'];
        $photo = $_FILES["facImage"]["name"];
        
        // $photo=$_FILES["photo"]["name"];
        // move_uploaded_file($_FILES["photo"]["tmp_name"],"studentimages/".$_FILES["photo"]["name"]);

        // File Validation
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        $file_extension = pathinfo($photo, PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
            exit();
        }

        if ($_FILES["facImage"]["size"] > 2 * 1024 * 1024) { // 2 MB limit
            echo "<script>alert('File size exceeds 2 MB.');</script>";
            exit();
        }

        move_uploaded_file($_FILES["facImage"]["tmp_name"], "img/Faculty-Profile/" .$_FILES["facImage"]["name"]);

        $query=mysqli_query($con, "insert into facmembers(emp_no,emp_name,grd_lvl,birthday,age,sex,cp,email,status,barangay,district,province,address,permission,facImage)value('$emp_no','$emp_name','$grd_lvl','$birthday','$age','$sex','$cp','$email','$status','$barangay','$district','$province','$address','$permission','$photo')");
        if ($query) {
            echo "<script>alert('Faculty Member has been added successfully!.');</script>"; 
            echo "<script>window.location.href = 'facrec.php'</script>";   
            $msg="";
        }
        else
        {
            echo "<script>alert('Something Went Wrong. Please try again.');</script>";    
        }
    }
}
?>


<!DOCTYPE html>
<html>
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
          <a class="dropdown-item" href="index.php">Logout</a>
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
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add Faculty Member</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Add Faculty Member</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Faculty Member Details</h3>
                                </div>
                                <!-- Form Start -->
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label for="emp_no">Employee No.</label>
                                                <input type="text" class="form-control" id="emp_no" name="emp_no" placeholder="Enter Employee No." required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="emp_name">Full Name</label>
                                                <input type="text" class="form-control" id="emp_name" name="emp_name" placeholder="Enter Full Name" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="birthday">Date of Birth</label>
                                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="age">Age</label>
                                                <input type="text" class="form-control" id="age" name="age" placeholder="Enter Age" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="sex">Sex</label>
                                                <select class="form-control" id="sex" name="sex" required>
                                                    <option>Select Sex</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="cp">Phone Number</label>
                                                <input type="text" class="form-control" id="cp" name="cp" placeholder="Enter Phone Number" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="grd_lvl">Grade Level</label>
                                                <input type="text" class="form-control" id="grd_lvl" name="grd_lvl" placeholder="Enter Grade Level" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="status">Status</label>
                                                <input type="text" class="form-control" id="status" name="status" placeholder="Enter Status" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="facImage">Photo</label>
                                                <input type="file" class="form-control" id="facImage" name="facImage" required>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5>Address</h5>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label for="barangay">Barangay</label>
                                                <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Enter Barangay" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="district">District</label>
                                                <input type="text" class="form-control" id="district" name="district" placeholder="Enter District" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="province">Province</label>
                                                <input type="text" class="form-control" id="province" name="province" placeholder="Enter Province" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="address">Complete Address</label>
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Complete Address" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="permission">Permission</label>
                                                <input type="text" class="form-control" id="permission" name="permission" placeholder="Enter Permission" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card Footer -->
                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
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