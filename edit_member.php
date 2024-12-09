<?php
session_start();
error_reporting(0);
include('includes/dbcon.php');

if (isset($_POST['submit'])) {
    $sid = $_SESSION['mem_id'];
    $employeeno = $_POST['emp_no'];
    $employeename = $_POST['emp_name'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];

    $sql = "UPDATE facmembers SET emp_no=:emp_no, emp_name=:emp_name, birthday=:birthday, age=:age, sex=:sex WHERE mem_id=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':emp_no', $employeeno, PDO::PARAM_STR);
    $query->bindParam(':emp_name', $employeename, PDO::PARAM_STR);
    $query->bindParam(':birthday', $birthday, PDO::PARAM_STR);
    $query->bindParam(':age', $age, PDO::PARAM_STR);
    $query->bindParam(':sex', $sex, PDO::PARAM_STR);
    $query->bindParam(':sid', $sid, PDO::PARAM_INT);
    $query->execute();
    // Check for errors and handle accordingly
}

if (isset($_POST['save'])) {
    $sid = $_SESSION['mem_id'];
    $cp = $_POST['cp'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $sql = "UPDATE facmembers SET cp=:cp, email=:email, status=:status WHERE mem_id=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cp', $cp, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':sid', $sid, PDO::PARAM_INT);
    $query->execute();
    // Check for errors and handle accordingly
}

if (isset($_POST['pass'])) {
    $sid = $_SESSION['mem_id'];
    $barangay = $_POST['barangay'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $address = $_POST['address'];

    $sql = "UPDATE facmembers SET barangay=:barangay, district=:district, province=:province, address=:address WHERE mem_id=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':barangay', $barangay, PDO::PARAM_STR);
    $query->bindParam(':district', $district, PDO::PARAM_STR);
    $query->bindParam(':province', $province, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':sid', $sid, PDO::PARAM_INT);
    $query->execute();
    // Check for errors and handle accordingly
}

if (isset($_POST['save2'])) {
    $sid = $_SESSION['mem_id'];
    $facimages = $_FILES["facimages"]["name"];
    $target_dir = "facimages/";
    $target_file = $target_dir . basename($facimages);
    $uploadOk = 1;

    // Check if image file is a valid image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["facimages"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["facimages"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["facimages"]["tmp_name"], $target_file)) {
            $sql = "UPDATE facmembers SET facImage=:facimages WHERE mem_id=:sid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':facimages', $facimages, PDO::PARAM_STR);
            $query->bindParam(':sid', $sid, PDO::PARAM_INT);
            $query->execute();
            // Check for errors and handle accordingly
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="card-body">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
       <?php
       $eid = $_POST['edit_id'];
       $ret = mysqli_query($con, "SELECT * FROM facmembers WHERE mem_id='$eid'");
       while ($row = mysqli_fetch_array($ret)) {
         $_SESSION['edid'] = $row['mem_id']; 
         ?>
         <div class="col-md-3">
           <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="img-circle" src="facimages/<?php echo htmlentities($row['facImage']); ?>" width="150" height="150" class="user-image" alt="User profile picture">
              </div>

              <h3 class="profile-username text-center"><?php echo $row['emp_name']; ?></h3>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Email</b> <a class="float-right"><?php echo $row['email']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Contact No</b> <a class="float-right"><?php echo $row['cp']; ?></a>
                </li>
              </ul>

            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#companydetail" data-toggle="tab">Faculty Member Details</a></li>
                <li class="nav-item"><a class="nav-link" href="#companyaddress" data-toggle="tab">Contact Info</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Address Info</a></li>
                <li class="nav-item"><a class="nav-link" href="#change" data-toggle="tab">Update Image</a></li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="companydetail">
                  <form method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                         <label for="empno">Employee Number</label>
                         <input name="emp_no" class="form-control" id="emp_no" value="<?php echo $row['emp_no']; ?>" required>
                       </div>
                     </div>
                     <div class="col-md-4">
                      <div class="form-group">
                        <label for="empname">Employee Name</label>
                        <input name="emp_name" class="form-control" id="emp_name" value="<?php echo $row['emp_name']; ?>" required>
                      </div>        
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Birthday</label>
                        <input type="date" class="form-control" name="birthday" value="<?php echo $row['birthday']; ?>" required>
                      </div>        
                    </div>
                    <div class="col-md-1" style="margin-left: 20px;">
                      <div class="form-group">
                        <label>Age</label>
                        <input class="form-control" name="age" value="<?php echo $row['age']; ?>" required>
                      </div>        
                    </div>

                    <div class="col-md-2" style="margin-left: 20px;">
                      <div class="form-group">
                        <label for="sex">Sex</label>
                        <select class="form-control" name="sex">
                          <option value="<?php echo $row['sex']; ?>"><?php echo $row['sex']; ?></option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </div>        
                    </div>

                  </div>
                  <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="companyaddress">
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="form-group">
                    <label for="cp">Contact Number</label>
                    <input name="cp" class="form-control" id="cp" value="<?php echo $row['cp']; ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="email">Email Address</label>
                    <input name="email" class="form-control" id="email" value="<?php echo $row['email']; ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="status">Status</label>
                    <input name="status" class="form-control" id="status" value="<?php echo $row['status']; ?>" required>
                  </div>

                  <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" name="save" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="settings">
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" class="form-control" required><?php echo $row['address']; ?></textarea>
                  </div>
                  <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" name="pass" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="change">
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="form-group">
                    <label for="facimages">Profile Image</label>
                    <input type="file" class="form-control" name="facimages" id="facimages" required>
                  </div>
                  <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" name="save2" class="btn btn-primary">Update Image</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
</div>