<?php
session_start();
error_reporting(0);
include('includes/dbcon.php');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

if (strlen($_SESSION['sid']==0)) {
 header('location:logout.php');
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

// Fetch Distinct Grade Levels
$gradeLevelQuery = "SELECT DISTINCT grd_lvl FROM facmembers";
$gradeLevels = $dbh->query($gradeLevelQuery)->fetchAll(PDO::FETCH_OBJ);

// Fetch Instructors with Grade Levels
$instructorQuery = "SELECT mem_id, emp_name, grd_lvl FROM facmembers";
$instructors = $dbh->query($instructorQuery)->fetchAll(PDO::FETCH_OBJ);
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instructor'])) {
    $instructor = escapeshellarg($_POST['instructor']);
    
    // Execute Python script and pass the instructor
    $command = "python py/generate_schedule.py " . $instructor;
    $output = shell_exec($command);

    // Return JSON response to AJAX
    header('Content-Type: application/json');
    echo $output;
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
    <link rel="stylesheet" href="css/style - facloading.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<header class="">
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
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="#" class="current-page"><i class='bx bx-loader' ></i></i><span class="nav-item">Faculty Loading</span></a>
                <span class="tooltip" id="active">Faculty Loading</span>
            </li>
            <li>
                <a href="facrec.php"><i class='bx bxs-group' ></i></i><span class="nav-item">Faculty Records</span></a>
                <span class="tooltip">Faculty Records</span>
            </li>
            <li>
                <a href="settings.php"><i class='bx bx-slider-alt'></i></i><span class="nav-item">Settings</span></a>
                <span class="tooltip">Settings</span>
            </li>
        </ul>

        <footer>
            <p>All rights Reserved.</p>
        </footer>
    </div>


    <div class="main-content">
        <div class="container-fluid">
            <section class="content">
                <div class="box box-default">
                    <div class="box-header">
                        <h5 class="box-title">Faculty Records</h5>
                    </div>
                    <div class="box-body">
                        <div style="width: 100%; display: block;">
                            
                            <div class="select-menu">
                                <p>Grade Level</p>
                                <div class="select-btn">
                                    <span class="sBtn-text">Select your option</span>
                                    <i class="bx bx-chevron-down"></i>
                                </div>
                                <ul class="options" id="grade-level-options">
                                <?php foreach($gradeLevels as $gradeLevel): ?>
                                    <li class="option" data-value="<?php echo htmlentities($gradeLevel->grd_lvl); ?>">
                                        <span class="option-text"><?php echo htmlentities($gradeLevel->grd_lvl); ?></span>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="select-menu">
                                <p>Instructor</p>
                                <div class="select-btn">
                                    <span class="sBtn-text">Select your option</span>
                                    <i class="bx bx-chevron-down"></i>
                                </div>
                                <ul class="options">
                                    
                                </ul>
                            </div>

                            <button class="btn-report" style="background: #df1414;" onclick="downloadPDF()">Download PDF Report</button>
                            <button class="btn-report" style="background: #4CAF50;" onclick="downloadExcel()">Download Excel Report</button>
                            
                            <!-- <h5 class="box-title">Faculty Loading</h5> -->
                            <div class="table-responsive">
                                <div class="controls">
                                    <label>
                                        <select id="rowsPerPage">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                        </select>
                                        entries per page
                                    </label>
                                    <span class="entries-info"></span>
                                </div>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="10%">Subject Name</th>
                                            <th width="25%">Room</th>
                                            <th width="15%">Section</th>
                                            <th width="15%">Day</th>
                                            <th width="20%">Start Time</th>
                                            <th width="20%">End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Schedule data will be populated here -->
                                    </tbody>
                                </table>
                                <div class="pagination-controls"></div>
                            </div>
                            
                            <button class="btn1 btn-view" onclick="generateSchedule()">Generate Schedule</button>
                            <button class="btn1 btn-view1" id="showScheduleButton" onclick="showSchedule()">Show Schedule</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
<!-- <script src="js/fac.js"></script> -->
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

<script>
    const optionMenus = document.querySelectorAll(".select-menu");
    const gradeLevelMenu = document.querySelector("#grade-level-options");  // Get the grade-level menu
    const instructorMenu = document.querySelector(".select-menu:nth-child(2) .options");  // Instructor options menu
    const instructorMenuWrapper = document.querySelector(".select-menu:nth-child(2)"); // Instructor menu wrapper (to toggle visibility)

    // Store all instructors to filter them later
    const allInstructors = <?php echo json_encode($instructors); ?>; // Fetch instructors from PHP

    optionMenus.forEach(optionMenu => {
        const selectBtn = optionMenu.querySelector(".select-btn");
        const options = optionMenu.querySelectorAll(".option");
        const sBtnText = optionMenu.querySelector(".sBtn-text");

        // Toggle dropdown on button click
        selectBtn.addEventListener("click", (e) => {
            e.stopPropagation();  // Prevent triggering document click listener
            closeOtherMenus(optionMenu);  // Close any open menus
            optionMenu.classList.toggle("active");  // Toggle current menu
        });

        // Update selected option text and close dropdown
        options.forEach(option => {
            option.addEventListener("click", () => {
                const selectedOption = option.querySelector(".option-text").innerText;
                sBtnText.innerText = selectedOption;
                optionMenu.classList.remove("active");  // Close menu after selection
            });
        });
    });

    // Close menus when clicking outside
    document.addEventListener("click", () => {
        optionMenus.forEach(menu => menu.classList.remove("active"));
    });

    // Close other open menus
    function closeOtherMenus(currentMenu) {
        optionMenus.forEach(menu => {
            if (menu !== currentMenu) {
                menu.classList.remove("active");
            }
        });
    }

    // Function to filter instructors based on selected grade level
    function filterInstructorsByGradeLevel(gradeLevel) {
        // Show the instructor menu wrapper once grade level is selected
        instructorMenuWrapper.style.display = "inline-block";

        // Clear existing options in the instructor dropdown
        instructorMenu.innerHTML = '';

        // Filter the instructors by grade level
        const filteredInstructors = allInstructors.filter(instructor => instructor.grd_lvl === gradeLevel);

        // Add filtered instructors to the dropdown
        if (filteredInstructors.length > 0) {
            filteredInstructors.forEach(instructor => {
                const option = document.createElement('li');
                option.classList.add('option');
                // Include both emp_name and mem_id as data attributes
                option.innerHTML = `<span class="option-text" data-id="${instructor.mem_id}">${instructor.emp_name}</span>`;
                instructorMenu.appendChild(option);
            });

            // Enable instructor selection
            const updatedOptions = instructorMenu.querySelectorAll('.option');
            updatedOptions.forEach(option => {
                option.addEventListener('click', () => {
                    const selectedInstructor = option.querySelector('.option-text').innerText; // Get the instructor name
                    const selectedMemId = option.querySelector('.option-text').dataset.id; // Get the mem_id

                    // Update the button text to the selected instructor's name
                    const instructorBtn = document.querySelector('.select-menu:nth-child(2) .sBtn-text');
                    instructorBtn.innerText = selectedInstructor;

                    // Store the selected mem_id in a hidden input or a global variable for later use
                    document.querySelector('.select-menu:nth-child(2)').dataset.selectedId = selectedMemId;

                    // Close the menu after selecting
                    instructorMenuWrapper.classList.remove('active');
                });
            });
        } else {
            // If no instructors for the selected grade, show a placeholder
            const option = document.createElement('li');
            option.classList.add('option');
            option.innerHTML = `<span class="option-text">No instructors available</span>`;
            instructorMenu.appendChild(option);
        }
    }


    // Event listener for grade level selection
    gradeLevelMenu.addEventListener('click', (e) => {
        if (e.target.classList.contains('option')) {
            const selectedGradeLevel = e.target.querySelector('.option-text').innerText;
            filterInstructorsByGradeLevel(selectedGradeLevel);  // Filter instructors based on the selected grade level
        }
    });
</script>

<script>
    function generateSchedule() {
        const selectedInstructor = document.querySelector('.select-menu:nth-child(2) .sBtn-text').innerText;

        if (selectedInstructor === "Select your option" || selectedInstructor === "No instructors available") {
            alert("Please select an instructor.");
            return;
        } else {
            const tables = document.querySelector(".table");

            // Create a table structure dynamically
            tables.innerHTML = `
                <thead>
                    <tr>
                        <th width="10%">Subject Name</th>
                        <th width="25%">Room</th>
                        <th width="15%">Section</th>
                        <th width="15%">Day</th>
                        <th width="20%">Start Time</th>
                        <th width="20%">End Time</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select id="subject-select">
                                <option value="">Select Subject</option>
                            </select>
                        </td>
                        <td>
                            <select id="room-select">
                                <option value="">Select Room</option>
                            </select>
                        </td>
                        <td>
                            <select id="section-select">
                                <option value="">Select Section</option>
                            </select>
                        </td>
                        <td>
                            <select id="day-select">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                        </td>
                        <td>
                            <select id="start-time">
                                <option value="01:00">01:00</option>
                                <option value="01:05">01:05</option>
                                <option value="01:10">01:10</option>
                                <option value="01:15">01:15</option>
                                <option value="01:20">01:20</option>
                                <option value="01:25">01:25</option>
                                <option value="01:30">01:30</option>
                                <option value="01:35">01:35</option>
                                <option value="01:40">01:40</option>
                                <option value="01:45">01:45</option>
                                <option value="01:50">01:50</option>
                                <option value="01:55">01:55</option>

                                <option value="02:00">02:00</option>
                                <option value="02:05">02:05</option>
                                <option value="02:10">02:10</option>
                                <option value="02:15">02:15</option>
                                <option value="02:20">02:20</option>
                                <option value="02:25">02:25</option>
                                <option value="02:30">02:30</option>
                                <option value="02:35">02:35</option>
                                <option value="02:40">02:40</option>
                                <option value="02:45">02:45</option>
                                <option value="02:50">02:50</option>
                                <option value="02:55">02:55</option>

                                <option value="03:00">03:00</option>
                                <option value="03:05">03:05</option>
                                <option value="03:10">03:10</option>
                                <option value="03:15">03:15</option>
                                <option value="03:20">03:20</option>
                                <option value="03:25">03:25</option>
                                <option value="03:30">03:30</option>
                                <option value="03:35">03:35</option>
                                <option value="03:40">03:40</option>
                                <option value="03:45">03:45</option>
                                <option value="03:50">03:50</option>
                                <option value="03:55">03:55</option>

                                <option value="04:00">04:00</option>
                                <option value="04:05">04:05</option>
                                <option value="04:10">04:10</option>
                                <option value="04:15">04:15</option>
                                <option value="04:20">04:20</option>
                                <option value="04:25">04:25</option>
                                <option value="04:30">04:30</option>
                                <option value="04:35">04:35</option>
                                <option value="04:40">04:40</option>
                                <option value="04:45">04:45</option>
                                <option value="04:50">04:50</option>
                                <option value="04:55">04:55</option>

                                <option value="05:00">05:00</option>
                                <option value="05:05">05:05</option>
                                <option value="05:10">05:10</option>
                                <option value="05:15">05:15</option>
                                <option value="05:20">05:20</option>
                                <option value="05:25">05:25</option>
                                <option value="05:30">05:30</option>
                                <option value="05:35">05:35</option>
                                <option value="05:40">05:40</option>
                                <option value="05:45">05:45</option>
                                <option value="05:50">05:50</option>
                                <option value="05:55">05:55</option>

                                <option value="06:00">06:00</option>
                                <option value="06:05">06:05</option>
                                <option value="06:10">06:10</option>
                                <option value="06:15">06:15</option>
                                <option value="06:20">06:20</option>
                                <option value="06:25">06:25</option>
                                <option value="06:30">06:30</option>
                                <option value="06:35">06:35</option>
                                <option value="06:40">06:40</option>
                                <option value="06:45">06:45</option>
                                <option value="06:50">06:50</option>
                                <option value="06:55">06:55</option>

                                <option value="07:00">07:00</option>
                                <option value="07:05">07:05</option>
                                <option value="07:10">07:10</option>
                                <option value="07:15">07:15</option>
                                <option value="07:20">07:20</option>
                                <option value="07:25">07:25</option>
                                <option value="07:30">07:30</option>
                                <option value="07:35">07:35</option>
                                <option value="07:40">07:40</option>
                                <option value="07:45">07:45</option>
                                <option value="07:50">07:50</option>
                                <option value="07:55">07:55</option>

                                <option value="08:00">08:00</option>
                                <option value="08:05">08:05</option>
                                <option value="08:10">08:10</option>
                                <option value="08:15">08:15</option>
                                <option value="08:20">08:20</option>
                                <option value="08:25">08:25</option>
                                <option value="08:30">08:30</option>
                                <option value="08:35">08:35</option>
                                <option value="08:40">08:40</option>
                                <option value="08:45">08:45</option>
                                <option value="08:50">08:50</option>
                                <option value="08:55">08:55</option>

                                <option value="09:00">09:00</option>
                                <option value="09:05">09:05</option>
                                <option value="09:10">09:10</option>
                                <option value="09:15">09:15</option>
                                <option value="09:20">09:20</option>
                                <option value="09:25">09:25</option>
                                <option value="09:30">09:30</option>
                                <option value="09:35">09:35</option>
                                <option value="09:40">09:40</option>
                                <option value="09:45">09:45</option>
                                <option value="09:50">09:50</option>
                                <option value="09:55">09:55</option>

                                <option value="10:00">10:00</option>
                                <option value="10:05">10:05</option>
                                <option value="10:10">10:10</option>
                                <option value="10:15">10:15</option>
                                <option value="10:20">10:20</option>
                                <option value="10:25">10:25</option>
                                <option value="10:30">10:30</option>
                                <option value="10:35">10:35</option>
                                <option value="10:40">10:40</option>
                                <option value="10:45">10:45</option>
                                <option value="10:50">10:50</option>
                                <option value="10:55">10:55</option>

                                <option value="11:00">11:00</option>
                                <option value="11:05">11:05</option>
                                <option value="11:10">11:10</option>
                                <option value="11:15">11:15</option>
                                <option value="11:20">11:20</option>
                                <option value="11:25">11:25</option>
                                <option value="11:30">11:30</option>
                                <option value="11:35">11:35</option>
                                <option value="11:40">11:40</option>
                                <option value="11:45">11:45</option>
                                <option value="11:50">11:50</option>
                                <option value="11:55">11:55</option>

                                <option value="12:00">12:00</option>
                                <option value="12:05">12:05</option>
                                <option value="12:10">12:10</option>
                                <option value="12:15">12:15</option>
                                <option value="12:20">12:20</option>
                                <option value="12:25">12:25</option>
                                <option value="12:30">12:30</option>
                                <option value="12:35">12:35</option>
                                <option value="12:40">12:40</option>
                                <option value="12:45">12:45</option>
                                <option value="12:50">12:50</option>
                                <option value="12:55">12:55</option>

                                <option value="13:00">13:00</option>
                                <option value="13:05">13:05</option>
                                <option value="13:10">13:10</option>
                                <option value="13:15">13:15</option>
                                <option value="13:20">13:20</option>
                                <option value="13:25">13:25</option>
                                <option value="13:30">13:30</option>
                                <option value="13:35">13:35</option>
                                <option value="13:40">13:40</option>
                                <option value="13:45">13:45</option>
                                <option value="13:50">13:50</option>
                                <option value="13:55">13:55</option>

                                <option value="14:00">14:00</option>
                                <option value="14:05">14:05</option>
                                <option value="14:10">14:10</option>
                                <option value="14:15">14:15</option>
                                <option value="14:20">14:20</option>
                                <option value="14:25">14:25</option>
                                <option value="14:30">14:30</option>
                                <option value="14:35">14:35</option>
                                <option value="14:40">14:40</option>
                                <option value="14:45">14:45</option>
                                <option value="14:50">14:50</option>
                                <option value="14:55">14:55</option>

                                <option value="15:00">15:00</option>
                                <option value="15:05">15:05</option>
                                <option value="15:10">15:10</option>
                                <option value="15:15">15:15</option>
                                <option value="15:20">15:20</option>
                                <option value="15:25">15:25</option>
                                <option value="15:30">15:30</option>
                                <option value="15:35">15:35</option>
                                <option value="15:40">15:40</option>
                                <option value="15:45">15:45</option>
                                <option value="15:50">15:50</option>
                                <option value="15:55">15:55</option>

                                <option value="16:00">16:00</option>
                                <option value="16:05">16:05</option>
                                <option value="16:10">16:10</option>
                                <option value="16:15">16:15</option>
                                <option value="16:20">16:20</option>
                                <option value="16:25">16:25</option>
                                <option value="16:30">16:30</option>
                                <option value="16:35">16:35</option>
                                <option value="16:40">16:40</option>
                                <option value="16:45">16:45</option>
                                <option value="16:50">16:50</option>
                                <option value="16:55">16:55</option>

                                <option value="17:00">17:00</option>
                                <option value="17:05">17:05</option>
                                <option value="17:10">17:10</option>
                                <option value="17:15">17:15</option>
                                <option value="17:20">17:20</option>
                                <option value="17:25">17:25</option>
                                <option value="17:30">17:30</option>
                                <option value="17:35">17:35</option>
                                <option value="17:40">17:40</option>
                                <option value="17:45">17:45</option>
                                <option value="17:50">17:50</option>
                                <option value="17:55">17:55</option>

                                <option value="18:00">18:00</option>
                                <option value="18:05">18:05</option>
                                <option value="18:10">18:10</option>
                                <option value="18:15">18:15</option>
                                <option value="18:20">18:20</option>
                                <option value="18:25">18:25</option>
                                <option value="18:30">18:30</option>
                                <option value="18:35">18:35</option>
                                <option value="18:40">18:40</option>
                                <option value="18:45">18:45</option>
                                <option value="18:50">18:50</option>
                                <option value="18:55">18:55</option>

                                <option value="19:00">19:00</option>
                                <option value="19:05">19:05</option>
                                <option value="19:10">19:10</option>
                                <option value="19:15">19:15</option>
                                <option value="19:20">19:20</option>
                                <option value="19:25">19:25</option>
                                <option value="19:30">19:30</option>
                                <option value="19:35">19:35</option>
                                <option value="19:40">19:40</option>
                                <option value="19:45">19:45</option>
                                <option value="19:50">19:50</option>
                                <option value="19:55">19:55</option>

                                <option value="20:00">20:00</option>
                                <option value="20:05">20:05</option>
                                <option value="20:10">20:10</option>
                                <option value="20:15">20:15</option>
                                <option value="20:20">20:20</option>
                                <option value="20:25">20:25</option>
                                <option value="20:30">20:30</option>
                                <option value="20:35">20:35</option>
                                <option value="20:40">20:40</option>
                                <option value="20:45">20:45</option>
                                <option value="20:50">20:50</option>
                                <option value="20:55">20:55</option>

                                <option value="21:00">21:00</option>
                                <option value="21:05">21:05</option>
                                <option value="21:10">21:10</option>
                                <option value="21:15">21:15</option>
                                <option value="21:20">21:20</option>
                                <option value="21:25">21:25</option>
                                <option value="21:30">21:30</option>
                                <option value="21:35">21:35</option>
                                <option value="21:40">21:40</option>
                                <option value="21:45">21:45</option>
                                <option value="21:50">21:50</option>
                                <option value="21:55">21:55</option>

                                <option value="22:00">22:00</option>
                                <option value="22:05">22:05</option>
                                <option value="22:10">22:10</option>
                                <option value="22:15">22:15</option>
                                <option value="22:20">22:20</option>
                                <option value="22:25">22:25</option>
                                <option value="22:30">22:30</option>
                                <option value="22:35">22:35</option>
                                <option value="22:40">22:40</option>
                                <option value="22:45">22:45</option>
                                <option value="22:50">22:50</option>
                                <option value="22:55">22:55</option>

                                <option value="23:00">23:00</option>
                                <option value="23:05">23:05</option>
                                <option value="23:10">23:10</option>
                                <option value="23:15">23:15</option>
                                <option value="23:20">23:20</option>
                                <option value="23:25">23:25</option>
                                <option value="23:30">23:30</option>
                                <option value="23:35">23:35</option>
                                <option value="23:40">23:40</option>
                                <option value="23:45">23:45</option>
                                <option value="23:50">23:50</option>
                                <option value="23:55">23:55</option>
                            </select>
                        </td>
                        <td>
                            <select id="end-time">
                                <option value="01:00">01:00</option>
                                <option value="01:05">01:05</option>
                                <option value="01:10">01:10</option>
                                <option value="01:15">01:15</option>
                                <option value="01:20">01:20</option>
                                <option value="01:25">01:25</option>
                                <option value="01:30">01:30</option>
                                <option value="01:35">01:35</option>
                                <option value="01:40">01:40</option>
                                <option value="01:45">01:45</option>
                                <option value="01:50">01:50</option>
                                <option value="01:55">01:55</option>

                                <option value="02:00">02:00</option>
                                <option value="02:05">02:05</option>
                                <option value="02:10">02:10</option>
                                <option value="02:15">02:15</option>
                                <option value="02:20">02:20</option>
                                <option value="02:25">02:25</option>
                                <option value="02:30">02:30</option>
                                <option value="02:35">02:35</option>
                                <option value="02:40">02:40</option>
                                <option value="02:45">02:45</option>
                                <option value="02:50">02:50</option>
                                <option value="02:55">02:55</option>

                                <option value="03:00">03:00</option>
                                <option value="03:05">03:05</option>
                                <option value="03:10">03:10</option>
                                <option value="03:15">03:15</option>
                                <option value="03:20">03:20</option>
                                <option value="03:25">03:25</option>
                                <option value="03:30">03:30</option>
                                <option value="03:35">03:35</option>
                                <option value="03:40">03:40</option>
                                <option value="03:45">03:45</option>
                                <option value="03:50">03:50</option>
                                <option value="03:55">03:55</option>

                                <option value="04:00">04:00</option>
                                <option value="04:05">04:05</option>
                                <option value="04:10">04:10</option>
                                <option value="04:15">04:15</option>
                                <option value="04:20">04:20</option>
                                <option value="04:25">04:25</option>
                                <option value="04:30">04:30</option>
                                <option value="04:35">04:35</option>
                                <option value="04:40">04:40</option>
                                <option value="04:45">04:45</option>
                                <option value="04:50">04:50</option>
                                <option value="04:55">04:55</option>

                                <option value="05:00">05:00</option>
                                <option value="05:05">05:05</option>
                                <option value="05:10">05:10</option>
                                <option value="05:15">05:15</option>
                                <option value="05:20">05:20</option>
                                <option value="05:25">05:25</option>
                                <option value="05:30">05:30</option>
                                <option value="05:35">05:35</option>
                                <option value="05:40">05:40</option>
                                <option value="05:45">05:45</option>
                                <option value="05:50">05:50</option>
                                <option value="05:55">05:55</option>

                                <option value="06:00">06:00</option>
                                <option value="06:05">06:05</option>
                                <option value="06:10">06:10</option>
                                <option value="06:15">06:15</option>
                                <option value="06:20">06:20</option>
                                <option value="06:25">06:25</option>
                                <option value="06:30">06:30</option>
                                <option value="06:35">06:35</option>
                                <option value="06:40">06:40</option>
                                <option value="06:45">06:45</option>
                                <option value="06:50">06:50</option>
                                <option value="06:55">06:55</option>

                                <option value="07:00">07:00</option>
                                <option value="07:05">07:05</option>
                                <option value="07:10">07:10</option>
                                <option value="07:15">07:15</option>
                                <option value="07:20">07:20</option>
                                <option value="07:25">07:25</option>
                                <option value="07:30">07:30</option>
                                <option value="07:35">07:35</option>
                                <option value="07:40">07:40</option>
                                <option value="07:45">07:45</option>
                                <option value="07:50">07:50</option>
                                <option value="07:55">07:55</option>

                                <option value="08:00">08:00</option>
                                <option value="08:05">08:05</option>
                                <option value="08:10">08:10</option>
                                <option value="08:15">08:15</option>
                                <option value="08:20">08:20</option>
                                <option value="08:25">08:25</option>
                                <option value="08:30">08:30</option>
                                <option value="08:35">08:35</option>
                                <option value="08:40">08:40</option>
                                <option value="08:45">08:45</option>
                                <option value="08:50">08:50</option>
                                <option value="08:55">08:55</option>

                                <option value="09:00">09:00</option>
                                <option value="09:05">09:05</option>
                                <option value="09:10">09:10</option>
                                <option value="09:15">09:15</option>
                                <option value="09:20">09:20</option>
                                <option value="09:25">09:25</option>
                                <option value="09:30">09:30</option>
                                <option value="09:35">09:35</option>
                                <option value="09:40">09:40</option>
                                <option value="09:45">09:45</option>
                                <option value="09:50">09:50</option>
                                <option value="09:55">09:55</option>

                                <option value="10:00">10:00</option>
                                <option value="10:05">10:05</option>
                                <option value="10:10">10:10</option>
                                <option value="10:15">10:15</option>
                                <option value="10:20">10:20</option>
                                <option value="10:25">10:25</option>
                                <option value="10:30">10:30</option>
                                <option value="10:35">10:35</option>
                                <option value="10:40">10:40</option>
                                <option value="10:45">10:45</option>
                                <option value="10:50">10:50</option>
                                <option value="10:55">10:55</option>

                                <option value="11:00">11:00</option>
                                <option value="11:05">11:05</option>
                                <option value="11:10">11:10</option>
                                <option value="11:15">11:15</option>
                                <option value="11:20">11:20</option>
                                <option value="11:25">11:25</option>
                                <option value="11:30">11:30</option>
                                <option value="11:35">11:35</option>
                                <option value="11:40">11:40</option>
                                <option value="11:45">11:45</option>
                                <option value="11:50">11:50</option>
                                <option value="11:55">11:55</option>

                                <option value="12:00">12:00</option>
                                <option value="12:05">12:05</option>
                                <option value="12:10">12:10</option>
                                <option value="12:15">12:15</option>
                                <option value="12:20">12:20</option>
                                <option value="12:25">12:25</option>
                                <option value="12:30">12:30</option>
                                <option value="12:35">12:35</option>
                                <option value="12:40">12:40</option>
                                <option value="12:45">12:45</option>
                                <option value="12:50">12:50</option>
                                <option value="12:55">12:55</option>

                                <option value="13:00">13:00</option>
                                <option value="13:05">13:05</option>
                                <option value="13:10">13:10</option>
                                <option value="13:15">13:15</option>
                                <option value="13:20">13:20</option>
                                <option value="13:25">13:25</option>
                                <option value="13:30">13:30</option>
                                <option value="13:35">13:35</option>
                                <option value="13:40">13:40</option>
                                <option value="13:45">13:45</option>
                                <option value="13:50">13:50</option>
                                <option value="13:55">13:55</option>

                                <option value="14:00">14:00</option>
                                <option value="14:05">14:05</option>
                                <option value="14:10">14:10</option>
                                <option value="14:15">14:15</option>
                                <option value="14:20">14:20</option>
                                <option value="14:25">14:25</option>
                                <option value="14:30">14:30</option>
                                <option value="14:35">14:35</option>
                                <option value="14:40">14:40</option>
                                <option value="14:45">14:45</option>
                                <option value="14:50">14:50</option>
                                <option value="14:55">14:55</option>

                                <option value="15:00">15:00</option>
                                <option value="15:05">15:05</option>
                                <option value="15:10">15:10</option>
                                <option value="15:15">15:15</option>
                                <option value="15:20">15:20</option>
                                <option value="15:25">15:25</option>
                                <option value="15:30">15:30</option>
                                <option value="15:35">15:35</option>
                                <option value="15:40">15:40</option>
                                <option value="15:45">15:45</option>
                                <option value="15:50">15:50</option>
                                <option value="15:55">15:55</option>

                                <option value="16:00">16:00</option>
                                <option value="16:05">16:05</option>
                                <option value="16:10">16:10</option>
                                <option value="16:15">16:15</option>
                                <option value="16:20">16:20</option>
                                <option value="16:25">16:25</option>
                                <option value="16:30">16:30</option>
                                <option value="16:35">16:35</option>
                                <option value="16:40">16:40</option>
                                <option value="16:45">16:45</option>
                                <option value="16:50">16:50</option>
                                <option value="16:55">16:55</option>

                                <option value="17:00">17:00</option>
                                <option value="17:05">17:05</option>
                                <option value="17:10">17:10</option>
                                <option value="17:15">17:15</option>
                                <option value="17:20">17:20</option>
                                <option value="17:25">17:25</option>
                                <option value="17:30">17:30</option>
                                <option value="17:35">17:35</option>
                                <option value="17:40">17:40</option>
                                <option value="17:45">17:45</option>
                                <option value="17:50">17:50</option>
                                <option value="17:55">17:55</option>

                                <option value="18:00">18:00</option>
                                <option value="18:05">18:05</option>
                                <option value="18:10">18:10</option>
                                <option value="18:15">18:15</option>
                                <option value="18:20">18:20</option>
                                <option value="18:25">18:25</option>
                                <option value="18:30">18:30</option>
                                <option value="18:35">18:35</option>
                                <option value="18:40">18:40</option>
                                <option value="18:45">18:45</option>
                                <option value="18:50">18:50</option>
                                <option value="18:55">18:55</option>

                                <option value="19:00">19:00</option>
                                <option value="19:05">19:05</option>
                                <option value="19:10">19:10</option>
                                <option value="19:15">19:15</option>
                                <option value="19:20">19:20</option>
                                <option value="19:25">19:25</option>
                                <option value="19:30">19:30</option>
                                <option value="19:35">19:35</option>
                                <option value="19:40">19:40</option>
                                <option value="19:45">19:45</option>
                                <option value="19:50">19:50</option>
                                <option value="19:55">19:55</option>

                                <option value="20:00">20:00</option>
                                <option value="20:05">20:05</option>
                                <option value="20:10">20:10</option>
                                <option value="20:15">20:15</option>
                                <option value="20:20">20:20</option>
                                <option value="20:25">20:25</option>
                                <option value="20:30">20:30</option>
                                <option value="20:35">20:35</option>
                                <option value="20:40">20:40</option>
                                <option value="20:45">20:45</option>
                                <option value="20:50">20:50</option>
                                <option value="20:55">20:55</option>

                                <option value="21:00">21:00</option>
                                <option value="21:05">21:05</option>
                                <option value="21:10">21:10</option>
                                <option value="21:15">21:15</option>
                                <option value="21:20">21:20</option>
                                <option value="21:25">21:25</option>
                                <option value="21:30">21:30</option>
                                <option value="21:35">21:35</option>
                                <option value="21:40">21:40</option>
                                <option value="21:45">21:45</option>
                                <option value="21:50">21:50</option>
                                <option value="21:55">21:55</option>

                                <option value="22:00">22:00</option>
                                <option value="22:05">22:05</option>
                                <option value="22:10">22:10</option>
                                <option value="22:15">22:15</option>
                                <option value="22:20">22:20</option>
                                <option value="22:25">22:25</option>
                                <option value="22:30">22:30</option>
                                <option value="22:35">22:35</option>
                                <option value="22:40">22:40</option>
                                <option value="22:45">22:45</option>
                                <option value="22:50">22:50</option>
                                <option value="22:55">22:55</option>

                                <option value="23:00">23:00</option>
                                <option value="23:05">23:05</option>
                                <option value="23:10">23:10</option>
                                <option value="23:15">23:15</option>
                                <option value="23:20">23:20</option>
                                <option value="23:25">23:25</option>
                                <option value="23:30">23:30</option>
                                <option value="23:35">23:35</option>
                                <option value="23:40">23:40</option>
                                <option value="23:45">23:45</option>
                                <option value="23:50">23:50</option>
                                <option value="23:55">23:55</option>
                            </select>
                        </td>
                        <td><button class="btn-submit" onclick="submitSchedules()">Submit Schedules</button></td>
                    </tr>
                </tbody>
            `;

            // Fetch data for the select boxes
            fetchDataForSelect('subjects', 'subject-select');
            fetchDataForSelect('rooms', 'room-select');
            fetchDataForSelect('sections', 'section-select');
        }
    }

    // Function to fetch data and populate a select box
    function fetchDataForSelect(type, selectId) {
        console.log(`Fetching data for type: ${type}`);
        fetch(`fetch_data.php?type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);  // Log the data for debugging
            if (Array.isArray(data)) {
                const select = document.getElementById(selectId);
                data.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    select.appendChild(option);
                });
            } else {
                console.error('Data is not an array:', data);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
    }

    function submitSchedules() {
        const subjectId = document.getElementById('subject-select').value;
        const roomId = document.getElementById('room-select').value;
        const sectionId = document.getElementById('section-select').value;
        const day = document.getElementById('day-select').value;
        const startTime = document.getElementById('start-time').value;
        const endTime = document.getElementById('end-time').value;
        const selectedInstructor = document.querySelector('.select-menu:nth-child(2) .sBtn-text').innerText;

        if (!subjectId || !roomId || !sectionId || !day || !startTime || !endTime) {
            alert("Please fill out all fields.");
            return;
        }

        const scheduleData = {
            subject_id: subjectId,
            room_id: roomId,
            section_id: sectionId,
            day: day,
            start_time: startTime,
            end_time: endTime,
            instructor: selectedInstructor
        };

        fetch('create_schedule.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(scheduleData)
        })
        .then(response => response.json())
        .then(result => {
            if (result.conflict) {
                alert("Schedule conflict detected. Please choose another time slot.");
            } else {
                alert("Schedule created successfully!");
            }
        });
    }

    function generateTimeOptions() {
        const stSelect = document.getElementById('start-time');
        const timeIntervals = 5; // Interval of 5 minutes
        const hoursInDay = 24; // 24 hours in a day

        for (let hour = 0; hour < hoursInDay; hour++) {
        for (let minute = 0; minute < 60; minute += timeIntervals) {
            // Format the hour and minute with leading zeros if necessary
            let formattedHour = hour.toString().padStart(2, '0');
            let formattedMinute = minute.toString().padStart(2, '0');
            
            // Create an option element
            let option = document.createElement('option');
            option.value = `${formattedHour}:${formattedMinute}`;
            option.textContent = `${formattedHour}:${formattedMinute}`;
            
            // Append the option to the select element
            stSelect.appendChild(option);
        }
        }
    }

    // Call the function to populate the select dropdown
    generateTimeOptions();

</script>

<script>
    let currentPage = 1; // Default to the first page
let rowsPerPage = 10; // Default number of rows per page
let isDataDisplayed = false; // Flag to track if data is displayed

document.addEventListener("DOMContentLoaded", () => {
    // Load the full schedule on initial page load
    showSchedule();
});

function showSchedule(page = 1, limit = 10) {
    currentPage = page;
    rowsPerPage = limit;

    // Get selected instructor ID, default to empty string to fetch all schedules
    const instructorMenu = document.querySelector('.select-menu:nth-child(2)');
    const memId = instructorMenu?.dataset.selectedId || '';

    // AJAX request to fetch the schedule
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_schedule.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.error) {
                alert(response.error);
                return;
            }

            // Populate the table with data
            const tbody = document.querySelector(".table tbody");
            tbody.innerHTML = response.data.map(row => `
                <tr>
                    <td>${row.subject}</td>
                    <td>${row.room}</td>
                    <td>${row.section}</td>
                    <td>${row.day}</td>
                    <td>${row.start_time}</td>
                    <td>${row.end_time}</td>
                </tr>
            `).join('');

            // Update pagination controls
            updatePagination(response.total, response.page, response.limit);
        }
    };

    xhr.send(`instructor=${encodeURIComponent(memId)}&limit=${limit}&page=${page}`);
}

function updatePagination(total, currentPage, limit) {
    const totalPages = Math.ceil(total / limit);
    const paginationControls = document.querySelector(".pagination-controls");
    paginationControls.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add('pagination-button');
        if (i === currentPage) {
            button.classList.add('active');
        }
        button.addEventListener('click', () => showSchedule(i, limit));
        paginationControls.appendChild(button);
    }
}

// Event listener for rows per page dropdown
document.querySelector('#rowsPerPage').addEventListener('change', (e) => {
    if (isDataDisplayed) {
        rowsPerPage = parseInt(e.target.value, 10); // Update rows per page globally
        showSchedule(1, rowsPerPage); // Reset to page 1 and reload data
    }
});

// Trigger fetching only when "Show Schedule" button is clicked
document.querySelector('#showScheduleButton').addEventListener('click', () => {
    const rowsDropdown = document.querySelector('#rowsPerPage');
    rowsPerPage = parseInt(rowsDropdown.value, 10); // Get the selected value
    showSchedule(1, rowsPerPage); // Fetch and display results
});


</script>

<script>
    function generateScheduleUsingML($instructorData) {
        $command = escapeshellcmd("python py/ml_api.py " . escapeshellarg(json_encode($instructorData)));
        $output = shell_exec($command);
        return json_decode($output, true);
    }

    function applyConstraintProgramming($predictedSchedule) {
        $command = escapeshellcmd("python py/cp_api.py " . escapeshellarg(json_encode($predictedSchedule)));
        $output = shell_exec($command);
        return json_decode($output, true);
    }
</script>
<script>
function downloadPDF() {
    const selectedInstructor = document.querySelector('.select-menu:nth-child(2) .sBtn-text').innerText;

    if (selectedInstructor === "Select your option" || selectedInstructor === "No instructors available") {
        alert("Please select an instructor.");
        return;
    }

    console.log(`generate_pdf.php?instructor=${encodeURIComponent(selectedInstructor)}`);
    window.location.href = `generate_pdf.php?instructor=${encodeURIComponent(selectedInstructor)}`;
}

function downloadExcel() {
    const selectedInstructor = document.querySelector('.select-menu:nth-child(2) .sBtn-text').innerText;

    if (selectedInstructor === "Select your option" || selectedInstructor === "No instructors available") {
        alert("Please select an instructor.");
        return;
    }

    console.log(`generate_excel.php?instructor=${encodeURIComponent(selectedInstructor)}`);
    window.location.href = `generate_excel.php?instructor=${encodeURIComponent(selectedInstructor)}`;
}
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>