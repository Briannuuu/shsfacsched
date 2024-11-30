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
          <a class="dropdown-item" href="facmem_login.php">Logout</a>
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
            <div class="box box-default">
                <div class="box-header">
                    <h5 class="box-title">Faculty Records</h5>
                </div>
                <div class="box-body">
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
                </div>
            </div>
        </div>
        
        <div class="container-fluid2">
            <div class="box box-default">
                <div class="box-header">
                    <h5 class="box-title">Faculty Loading</h5>
                </div>
                <div class="box-body">
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
            </div>
        </div>
        <button class="btn1 btn-view" onclick="generateSchedule()">Generate Schedule</button>
        <button class="btn1 btn-view1" onclick="showSchedule()">Show Schedule</button>
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
        instructorMenuWrapper.style.display = "block";

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
        }

        // Send AJAX request to trigger schedule generation
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "generate_schedule.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Populate the table with the response from PHP
                document.querySelector(".table tbody").innerHTML = xhr.responseText;
            }
        };
        xhr.send("instructor=" + encodeURIComponent(selectedInstructor));
    }

</script>

<script>
    let currentPage = 1; // Default to the first page
let rowsPerPage = 10; // Default number of rows per page
let isDataDisplayed = false; // Flag to track if data is displayed

function showSchedule(page = 1, limit = rowsPerPage) {
    currentPage = page; // Update current page globally
    rowsPerPage = limit; // Update rows per page globally

    const instructorMenu = document.querySelector('.select-menu:nth-child(2)');
    const memId = instructorMenu.dataset.selectedId; // Get the selected mem_id

    if (!memId) {
        alert("Please select a valid instructor.");
        return;
    }

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

            // Set the flag to true once data is displayed
            isDataDisplayed = true;
        }
    };
    xhr.send(`instructor=${encodeURIComponent(memId)}&limit=${limit}&page=${page}`);
}

function updatePagination(total, currentPage, limit) {
    const totalPages = Math.ceil(total / limit);
    const paginationControls = document.querySelector(".pagination-controls");

    // Update pagination controls
    paginationControls.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.classList.add('pagination-button');
        if (i === currentPage) {
            pageButton.classList.add('active'); // Highlight the current page
        }

        // Add event listener for pagination button click
        pageButton.addEventListener('click', () => {
            if (isDataDisplayed) {
                showSchedule(i, rowsPerPage); // Fetch data for the selected page
            }
        });

        paginationControls.appendChild(pageButton);
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
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>