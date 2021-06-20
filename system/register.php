<?php 

include('pages/database_connection.php');
include('pages/function.php');

$message = '';
if (count_users($connect) != 0){
  header("location:login.php");
}
if(isset($_POST["btn-create"]))
{
  if ($_POST["user_password"] != $_POST["retype_password"]){
    $message = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>Password does not match!</div>';
  }
  else{
    $query = "
    INSERT INTO user_account (user_email, user_password, user_type) 
    VALUES (:user_email, :user_password, :user_type)
    ";	
    $statement = $connect->prepare($query);
    $result = $statement->execute(
      array(
        ':user_email'		  =>	trim($_POST["user_email"]),
        ':user_password'	=>	password_hash(trim($_POST["user_password"]), PASSWORD_DEFAULT),
        ':user_type'		  =>	'Admin'
      )
    );
    //$result = $statement->fetchAll();
    if(isset($result))
    {
      header("location:login.php");
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

<style type="text/css">
  .login-page {
    background: url("assets/Muntinlupa_Sports_Center.jpg") no-repeat center center fixed;
    background-size: cover;	
  }
</style>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->

  <span id="alert_action"><?php echo $message?></span>
  <div class="card card-outline">
    <div class="card-body card-primary">

       <div class="text-center">
                <img style="height:175px"
                src="assets/yasdo_logo.png"
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
                <input type="email" class="form-control" name="user_email" id="user_email" placeholder="Username">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                <input type="password" class="form-control"  name="user_password" id="user_password" placeholder="Password">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                <input type="password" class="form-control"  name="retype_password" id="retype_password" placeholder="Confirm Password">
            </div>

          <div class="social-auth-links text-center mt-2 mb-3">
                <button type="submit" class="btn btn-warning btn-block" name="btn-create" id="btn-create">Create Account</button>
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