<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbcon.php');

if(isset($_POST['login'])) 
{
  $username=$_POST['username'];
  $password=md5($_POST['password']);
  $sql ="SELECT * FROM facmembers WHERE emp_no=:username and facpass=:password ";
  $query=$dbh->prepare($sql);
  $query-> bindParam(':username', $username, PDO::PARAM_STR);
  $query-> bindParam(':password', $password, PDO::PARAM_STR);
  $query-> execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach ($results as $result) {
      $_SESSION['sid']=$result->mem_id;
      $_SESSION['name']=$result->emp_name;
      $_SESSION['email']=$result->email;
      $_SESSION['contactno']=$result->cp;
      $_SESSION['permission']=$result->permission;
    }

    if (!empty($_POST["remember"])) {
        // Set cookies for user login and password if remember me is checked
        setcookie("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60)); // 10 years
        setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60)); // 10 years
    } else {
        // Remove the cookies if remember me is not checked
        if (isset($_COOKIE["user_login"])) {
            setcookie("user_login", "", time() - 3600); // Expire the cookie
        }
        if (isset($_COOKIE["userpassword"])) {
            setcookie("userpassword", "", time() - 3600); // Expire the cookie
        }
    }
    
    $aa= $_SESSION['sid'];
    $sql="SELECT * from facmembers where mem_id=:aa";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':aa',$aa,PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);

    $cnt=1;
    if($query->rowCount() > 0)
    {
      foreach($results as $row)
      {               

        if($row->status=="Active"){ 
          if ($row->permission == "User") {
            $extra = "facmem_home.php"; 
          } else {
            $extra = "#";
          }

          $username=$_POST['username'];
          $email=$_SESSION['email'];
          $emp_name=$_SESSION['name'];
          $_SESSION['login']=$_POST['username'];
          $_SESSION['sid']=$row->mem_id;
          $_SESSION['username']=$row->emp_name;
          $uip=$_SERVER['REMOTE_ADDR'];
          $status=1;
          $sql="insert into userlog(userEmail,userip,status,username,name)values(:email,:uip,:status,:username,:name)";
          $query=$dbh->prepare($sql);
          $query->bindParam(':email',$email,PDO::PARAM_STR);
          $query->bindParam(':uip',$uip,PDO::PARAM_STR);
          $query->bindParam(':status',$status,PDO::PARAM_STR);
          $query->bindParam(':username',$username,PDO::PARAM_STR);
          $query->bindParam(':name',$emp_name,PDO::PARAM_STR);
          $query->execute();
          $host=$_SERVER['HTTP_HOST'];
          $uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
          header("location:http://$host$uri/$extra");
          exit();                 
        } else { 
          echo "<script>alert('Your account was blocked please approach Admin');document.location ='facmem_login.php';</script>";                                        
        }  

      } } 
    } else{ 
      $extra="facmem_login.php";
      $username=$_POST['username'];
      $uip=$_SERVER['REMOTE_ADDR'];
      $status=0;
      $email='Not registered in system';
      $emp_name='Potential Hacker';
      $sql="insert into userlog(userEmail,userip,status,username,name)values(:email,:uip,:status,:username,:name)";
      $query=$dbh->prepare($sql);
      $query->bindParam(':email',$email,PDO::PARAM_STR);
      $query->bindParam(':uip',$uip,PDO::PARAM_STR);
      $query->bindParam(':status',$status,PDO::PARAM_STR);
      $query->bindParam(':username',$username,PDO::PARAM_STR);
      $query->bindParam(':name',$emp_name,PDO::PARAM_STR);
      $query->execute();
      $host  = $_SERVER['HTTP_HOST'];
      $uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
      echo "<script>alert('Username or Password is incorrect');document.location ='http://$host$uri/$extra';</script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style - login.css">
    <title>Sagad High School Loading System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo.png">
</head>
<body>
    <div class="container">
        <div class="half image-half">
            <div class="logo-container">
                <img src="img/logo.png" alt="logo" class="logo">
            </div> 

            <section>
                <div class="form-box">
                        <div class="form-value">
                            <form class action="" method="post">
                                    <div class="inputbox">
                                        <ion-icon name="person-circle-outline"></ion-icon>
                                        <input type="text" name="username" placeholder="Employee Number" required value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>">
                                    </div>
    
                                    <div class="inputbox">
                                        <ion-icon name="lock-closed-outline"></ion-icon>
                                        <input type="password" name="password" placeholder="Password" required value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
                                    </div>
    
                                    <div class="forget">
                                        <label for=""><input type="checkbox" id="remember" name="remember" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>Remember Me</label>
                                    </div>
    
                                    <div class="btn-field">
                                        <button type="submit" name="login" data-toggle="modal" data-taget="#modal-default">LOGIN</button>   
                                    </div>
                            </form>    
                        </div>
                </div>
            </section>
        </div>
      </div>
</body>
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>