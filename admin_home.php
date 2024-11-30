<?php
session_start();
error_reporting(0);
include('includes/dbcon.php');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

// Check if session ID is set, if not, redirect to logout
if (empty($_SESSION['sid'])) {
    header('location:logout.php');
    exit();
}

// If the session destroy request is received (from the back button)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'destroy') {
    // Only destroy the session if "remember me" was not checked
    if (empty($_COOKIE['user_login']) && empty($_COOKIE['userpassword'])) {
        session_unset();
        session_destroy();
    }
    exit(); // Stop further processing
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sagad High School Loading System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo.png">
    <link rel="stylesheet" href="css/style - adminhome.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- <link rel="stylesheet" href="css/adminlte.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<header class="">
    <div class="header-logo">
        <img src="img/logo.png" alt="logo" class="logo">
    </div>
    <div class="dropdown">
        <button class="dropbtn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><ion-icon name="log-out-outline"></ion-icon></button>
        <ul class="dropdown-content">
          <a class="dropdown-item" href="logout.php">Logout</a>
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
                    $sql="SELECT * from tbladmin   where admin_id=:eid ";                                    
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
                <a href="#" class="current-page"><i class = "bx bxs-dashboard"></i><span class="nav-item">Dashboard</span></a>
            </li>
            <li>
                <a href="facloading.php"><i class='bx bx-loader' ></i></i><span class="nav-item">Faculty Loading</span></a>
            </li>
            <li>
                <a href="facrec.php"><i class='bx bxs-group' ></i></i><span class="nav-item">Faculty Records</span></a>
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
        <div class="container-fluid">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <?php $query1=mysqli_query($con,"Select * from facmembers ");
                                $totalcust=mysqli_num_rows($query1);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalcust;?></h3>
                                    <p>Total of Faculty Members</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="facrec.php" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <?php $query2=mysqli_query($con,"Select * from facmembers where sex='Male'");
                                $totalmale=mysqli_num_rows($query2);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalmale;?></h3>

                                    <p>Total Male Faculty Members</p>
                                </div>
                                <div class="icon">
                                    <i class="ionicons ion-male"></i>
                                </div>
                                <a href="facrec.php" onclick="filterStudents('male')" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <?php $query3=mysqli_query($con,"Select * from facmembers where sex='Female'");
                                $totalfemale=mysqli_num_rows($query3);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalfemale;?></h3>

                                    <p>Total Female Faculty Members</p>
                                </div>
                                <div class="icon">
                                    <i class="ionicons ion-female"></i>
                                </div>
                                <a href="facrec.php" onclick="filterStudents('female')" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <!-- ./col -->

                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <?php $query3=mysqli_query($con,"Select * from facmembers where status='Active'");
                                $totalfemale=mysqli_num_rows($query3);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalfemale;?></h3>

                                    <p>Total List of Active Faculty Members</p>
                                </div>
                                <div class="icon">
                                    <i class="ionicons ion-android-list"></i>
                                </div>
                                <a href="facrec.php" onclick="filterOccu('Active')" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <?php $query3=mysqli_query($con,"Select * from facmembers where status='Inactive'");
                                $totalfemale=mysqli_num_rows($query3);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalfemale;?></h3>

                                    <p>Total List of Inactive Faculty Members</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="facrec.php" onclick="filterOccu('Inactive')" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <?php $query3=mysqli_query($con,"Select * from facmembers where grd_lvl='10'");
                                $totalfemale=mysqli_num_rows($query3);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalfemale;?></h3>

                                    <p>Total List of Grade 10 Handles</p>
                                </div>
                                <div class="icon">
                                    <i class="ionicons ion-university"></i>
                                </div>
                                <a href="facrec.php" onclick="filterOccu('10')" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="scheduleChart"></canvas>
                    </div>
                </div>

                
                <!-- /.row (main row) -->
            </div>
            <!-- /.container-fluid -->
        </section>
        </div>
    </div>
</body>
<script>
        const ctx = document.getElementById('scheduleChart').getContext('2d');

        // Example schedule data
        const labels = ['6:00 AM', '6:40 AM', '7:20 AM', '8:00 AM', '8:40 AM', '9:20 AM'];
        const datasets = [
            {
                label: 'Monday',
                data: [1, 2, 3, 4, 5, 6], // Replace with Monday's subject schedule indices
                borderColor: 'blue',
                fill: false,
            },
            {
                label: 'Tuesday',
                data: [6, 5, 4, 3, 2, 1], // Replace with Tuesday's subject schedule indices
                borderColor: 'green',
                fill: false,
            },
            {
                label: 'Wednesday',
                data: [2, 4, 6, 1, 3, 5], // Replace with Wednesday's subject schedule indices
                borderColor: 'red',
                fill: false,
            },
            {
                label: 'Thursday',
                data: [3, 1, 4, 6, 2, 5], // Replace with Thursday's subject schedule indices
                borderColor: 'orange',
                fill: false,
            },
            {
                label: 'Friday',
                data: [5, 3, 1, 6, 4, 2], // Replace with Friday's subject schedule indices
                borderColor: 'purple',
                fill: false,
            },
        ];


        const scheduleChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Subjects',
                        },
                        ticks: {
                            callback: function(value) {
                                // Replace subject codes with names
                                const subjects = ['TLE', 'Mapeh', 'Values Education', 'AP', 'Science', 'Recess'];
                                return subjects[value - 1];
                            },
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Time',
                        },
                    },
                },
            },
        });
    </script>
<script>
    function filterStudents(gender) {
        if (gender === 'male') {
            window.location.href = 'facrec.php?filter=male';
        } else if (gender === 'female') {
            window.location.href = 'facrec.php?filter=female';
        } 
    }
</script>
<script>
    function filterOccu(status) {
        if (status === 'Active') {
            window.location.href = 'facrec.php?filter=active';
        } else if (status === 'Inactive') {
            window.location.href = 'facrec.php?filter=inactive';
        } else if (status === '10') {
            window.location.href = 'facrec.php?filter=10';
        } 
    }
</script>
<script>
    let btn = document.querySelector('#btn');
    let sidebar = document.querySelector('.sidebar');

    btn.onclick = function (){
        sidebar.classList.toggle('active');
    };
</script>

<script>
    window.onpopstate = function(event) {
    // Send an AJAX request to destroy the session
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "logout.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("action=destroy");
    };
</script>

<script>
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>