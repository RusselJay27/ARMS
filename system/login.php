<?php 

include('pages/database_connection.php');
include('pages/function.php');

if (count_users($connect) == 0){
  header("location:register.php");
  //echo 'No User';
}
else{
  //echo 'Have User';
  $message = '';
  if(isset($_SESSION['user_type']))
  {
    header("location:pages/index.php");
  }
  
  if(isset($_POST["btn-login"]))
  {
    $query = "
    SELECT * FROM user_account 
      WHERE user_name = :user_name
    ";

    $statement = $connect->prepare($query);
    $statement->execute(
      array(
          'user_name'  =>  $_POST["user_name"]
        )
    );

    $count = $statement->rowCount();

    if($count > 0)
    {
        $result = $statement->fetchAll();
          foreach($result as $row)
          {
              if($row['user_status'] == 'Active')
              {
                  if(password_verify($_POST["user_password"], $row["user_password"]))
                  {
                    $_SESSION['user_type'] = $row['user_type'];
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_name'] = $row['user_name'];
                    header("location:pages/index.php");
                  }
                  else
                  {
                    $message = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>Wrong Password!</div>';
                  }
              }
              else
              {
                $message = '<div class="alert alert-warning alert-dismissible"><button type="button"class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>Your account is inactive, please contact your administrator!</div>';
              }
          }
    }

    else
    {
      $message = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>Invalid Account!</div>';
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Athlete Portal | Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->

  <span id="alert_action"><?php echo $message?></span>
  <div class="card card-outline">
    <div class="card-body card-primary">

       <div class="text-center">
            <img class="profile-user-img img-fluid img-circle"
                src="dist/img/login_user.png"
                alt="User profile picture"> 
        </div>
        <br/>
        <form method="post">
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Username">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                <input type="password" class="form-control"  name="user_password" id="user_password" placeholder="Password">
            </div>

          <div class="social-auth-links text-center mt-2 mb-3">
                <button type="submit" class="btn btn-warning btn-block" name="btn-login" id="btn-login">Login</button>
          </div> 
        </form>

      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>