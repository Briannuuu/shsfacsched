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
    <link rel="stylesheet" href="css/settings.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts and Icons -->
    <link rel="shortcut icon" href="img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
                <a href="facrec.php"><i class='bx bxs-group' ></i></i><span class="nav-item">Faculty Records</span></a>
            </li>
            <li>
                <a href="#" class="current-page"><i class='bx bx-slider-alt'></i></i><span class="nav-item">Settings</span></a>
            </li>
        </ul>

        <footer>
            <p>All rights Reserved.</p>
        </footer>
    </div>


    <div class="main-content">
        <div class="container light-style flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-4">
                Account Settings
            </h4>

            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <!-- General Tab -->
                            <div class="tab-pane fade active show" id="account-general">
                                <div class="card-body media align-items-center">
                                    <img src="staff_images/<?php echo htmlentities($row->u_image);?>" alt="User profile picture" width="90px" height="90px"
                                    class="user-image">
                                    <div class="media-body ml-4">
                                        <label class="btn btn-outline-primary">
                                            Upload New Photo
                                            <input type="file" class="account-settings-fileinput">
                                        </label> &nbsp;
                                        <button type="button" class="btn btn-default md-btn-flat">Reset</button>
                                        <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <!-- Non-editable fields -->
                                    <div class="form-group">
                                        <label class="form-label">Admin ID</label>
                                        <input type="text" class="form-control mb-1" value="<?php echo htmlspecialchars($row->admin_id); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row->name); ?>" readonly>
                                    </div>

                                    <!-- Editable fields -->
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control mb-1" value="<?php echo htmlspecialchars($row->email_add);?>">
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <select class="custom-select">
                                            <option selected>Active</option>
                                            <option>Inactive</option>
                                        </select>
                                    </div> -->
                                </div>
                            </div>

                            <!-- Change Password Tab -->
                            <div class="tab-pane fade" id="account-change-password">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <input type="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <input type="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <input type="password" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Info Tab -->
                            <div class="tab-pane fade" id="account-info">
                                <div class="card-body">
                                    <!-- Personal Info -->
                                    <!-- <div class="form-group">
                                        <label class="form-label">Birthday</label>
                                        <input type="date" class="form-control" value="">
                                    </div> -->

                                    <!-- Address Selector -->
                                    <!-- <div class="form-group">
                                        <label class="form-label">Region *</label>
                                        <select name="region" class="form-control form-control-md" id="region"></select>
                                        <input type="hidden" class="form-control form-control-md" name="region_text"
                                            id="region-text" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Province *</label>
                                        <select name="province" class="form-control form-control-md" id="province"></select>
                                        <input type="hidden" class="form-control form-control-md" name="province_text"
                                            id="province-text" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">City / Municipality *</label>
                                        <select name="city" class="form-control form-control-md" id="city"></select>
                                        <input type="hidden" class="form-control form-control-md" name="city_text"
                                            id="city-text" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Barangay *</label>
                                        <select name="barangay" class="form-control form-control-md" id="barangay"></select>
                                        <input type="hidden" class="form-control form-control-md" name="barangay_text"
                                            id="barangay-text" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="street-text" class="form-label">Street (Optional)</label>
                                        <input type="text" class="form-control form-control-md" name="street_text"
                                            id="street-text">
                                    </div> -->

                                    <!-- Contact Number -->
                                    <div class="form-group">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row->contact_no);?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right mt-3">
                        <button type="button" id="submit-profile" class="btn btn-save">Save changes</button>&nbsp;
                        <button type="button" class="btn btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    

</body>
</html>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let btn = document.querySelector('#btn');
        let sidebar = document.querySelector('.sidebar');
        
        btn.onclick = function (){
            sidebar.classList.toggle('active');
        };
    </script>
    