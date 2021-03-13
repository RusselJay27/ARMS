<?php
include('../database_connection.php');
//include('function.php');
if(!isset($_SESSION["user_type"]))
{
  header("location:../../login.php");
}
$_SESSION['tournaments_id'] ='';
$_SESSION['tournaments_name'] ='';
$_SESSION['coaches_id'] = '';
$_SESSION['coaches_fullname'] = '';
$_SESSION['athletes_id'] ='';
$_SESSION['athletes_fullname'] ='';

$query = "
  SELECT * FROM user_account
  WHERE user_id = '".$_SESSION["user_id"]."'
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $full_name = '';
  $username = '';
  foreach($result as $row)
    {
      $full_name = $row['user_last'].", ".$row['user_first']." ".$row['user_mi'].".";
      $user_name = $row['user_name'];
    } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $_SESSION['user_type']; ?> Portal | Profile</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed text-sm">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-light navbar-warning border-bottom-0 accent-warning">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
          <img style="height:30px; width:125px"
                src="../../assets/yasdo_logo.png"
                alt="User profile picture"> 
      </li>
    </ul>

  </nav>

  <aside class="main-sidebar sidebar-light-warning elevation-4">
    <a href="#" class="brand-link navbar-warning">
      <img src="../../dist/img/login_user.png" alt="User Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"> <?php echo $_SESSION["user_name"]; ?></span>
    </a>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="./../index.php" class="nav-link">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'Admin'){?>
            <li class="nav-item">
                <a href="./../schools/" class="nav-link">
                    <i class="far fa-building nav-icon"></i>
                    <p>Schools</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./../grade_level/" class="nav-link">
                    <i class="fa fa-book nav-icon"></i>
                    <p>Grade Level</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./../sports/" class="nav-link">
                    <i class="fa fa-basketball-ball nav-icon"></i>
                    <p>Sports</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./../tournaments/" class="nav-link">
                    <i class="ion ion-stats-bars nav-icon"></i>
                    <p>Tournaments</p>
                </a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a href="./../coaches/" class="nav-link">
                    <i class="far fa-user nav-icon"></i>
                    <p>Coaches</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./../athletes/" class="nav-link">
                    <i class="fa fa-child nav-icon"></i>
                    <p>Athletes</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./../report/" class="nav-link">
                    <i class="fa fa-download nav-icon"></i>
                    <p>Report</p>
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'Admin'){?>
            <li class="nav-item">
                <a href="./../users/" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Users</p>
                </a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-user nav-icon"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../../logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
    	<span id="alert_action"></span>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-body">
                <form method="post" id="profile_form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo $full_name; ?>"  disabled />
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $user_name; ?>"  />
                    </div>
                    <hr />
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Re-enter Password</label>
                        <input type="password" name="re_user_password" id="re_user_password" class="form-control" required/>
                        <span id="error_password"></span> 
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btn-save" id="btn-save" value="Save" class="btn btn-warning" />
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <footer class="main-footer">
    <strong>Copyright &copy; 2021</strong>
    All rights reserved.
  </footer>

</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->

<script>
$(document).ready(function(){
  $('#profile_form').on('submit', function(event){
    event.preventDefault();
    if($('#user_password').val() != '')
    {
      if($('#user_password').val() != $('#re_user_password').val())
      {
        $('#error_password').html('<label class="text-danger">Password Not Match</label>');
        return false;
      }
      else
      {
        $('#error_password').html('');
      }
    }
    $('#btn-save').attr('disabled', 'disabled');
    var form_data = $(this).serialize();
    $('#re_user_password').attr('required',false);
    $.ajax({
      url:"action.php",
      method:"POST",
      data:form_data,
      success:function(data)
      {
        $('#btn-save').attr('disabled', false);
        $('#user_password').val('');
        $('#re_user_password').val('');
        $('#alert_action').html(data);
      }
    })
  });
});
</script>

</body>
</html>