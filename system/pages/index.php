<?php
include('database_connection.php');
include('function.php');
if(!isset($_SESSION["user_type"]))
{
  header("location:../login.php");
}
$_SESSION['tournaments_id'] ='';
$_SESSION['tournaments_name'] ='';
$_SESSION['coaches_id'] = '';
$_SESSION['coaches_fullname'] = '';
$_SESSION['athletes_id'] ='';
$_SESSION['athletes_fullname'] ='';

    $query = "SELECT * FROM tournaments";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{	
      $now = time(); // or your date as well
      $your_date = strtotime($row['date']);
      $datediff = $now - $your_date;
      $days = round($datediff / (60 * 60 * 24));
      if ($days < 2){
        //echo 'active ';
        //echo $days.' - ey';
      }
      else{
        $query1 = "
        UPDATE tournaments 
        SET tournaments_status = 'Inactive'
        WHERE tournaments_id = :tournaments_id
        ";
        $statement1 = $connect->prepare($query1);
        $statement1->execute(
          array(
            ':tournaments_id'		=>	$row["tournaments_id"]
          )
        );
      }
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $_SESSION['user_type']; ?> Portal | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed text-sm">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-light navbar-warning border-bottom-0 accent-warning">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
          <img style="height:30px; width:125px"
                src="../assets/yasdo_logo.png"
                alt="User profile picture"> 
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-warning elevation-4">
    <a href="#" class="brand-link navbar-warning">
      <img src="../dist/img/login_user.png" alt="User Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">   <?php echo $_SESSION["user_name"]; ?></span>
    </a>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               
            <?php if ($_SESSION['user_type'] == 'Coach'){?>
              <li class="nav-item">
                  <a href="#" class="nav-link active">
                      <i class="fa fa-child nav-icon"></i>
                      <p>Athletes</p>
                  </a>
              </li>
            <?php } else {?>
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./schools/" class="nav-link">
                    <i class="far fa-building nav-icon"></i>
                    <p>Schools</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./grade_level/" class="nav-link">
                    <i class="fa fa-book nav-icon"></i>
                    <p>Grade Level</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./sports/" class="nav-link">
                    <i class="fa fa-basketball-ball nav-icon"></i>
                    <p>Sports</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./tournaments/" class="nav-link">
                    <i class="ion ion-stats-bars nav-icon"></i>
                    <p>Tournaments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./coaches/" class="nav-link">
                    <i class="far fa-user nav-icon"></i>
                    <p>Coaches</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./athletes/" class="nav-link">
                    <i class="fa fa-child nav-icon"></i>
                    <p>Athletes</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./report/" class="nav-link">
                    <i class="fa fa-download nav-icon"></i>
                    <p>Report</p>
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'Admin'){?>
            <li class="nav-item">
                <a href="./users/" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Users</p>
                </a>
            </li>
            <?php }}?>
            <li class="nav-item">
                <a href="./profile/" class="nav-link">
                    <i class="fas fa-user nav-icon"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./../logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo count_athletes($connect);?></h3>
                <p>Athletes/Students</p>
              </div>
              <div class="icon">
                <i class="fa fa-child"></i>
              </div>
              <a href="./athletes/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo count_users_dashboard($connect);?></h3>
                <p>User Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="./users/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo count_tournaments($connect);?></h3>
                <p>Tournaments/Competitions</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="./tournaments/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo count_schools($connect);?></h3>

                <p>Schools</p>
              </div>
              <div class="icon">
                <i class="far fa-building"></i>
              </div>
              <a href="./schools/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">

                <div class="card">
                  <div class="card-header border-0">
                    <h3 class="card-title">List of Tournaments and Athletes</h3>
                  </div>
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>Previous Award</th>
                        <th>Coach</th>
                        <th>Athlete</th>
                        <th>Sports</th>
                        <th>Tournament</th>
                        <th>Date Event</th>
                      </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="content">
          <div class="col-lg-12">
            
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Ranking</h3>
                <div class="card-tools">
                    <div class="form-group">
                      <select name="ranking_tournament_id" id="ranking_tournament_id" class="form-control" required>
                        <option value="">Select Tournament</option>
                        <?php echo fill_tournaments_ranking_list($connect) ?> 
                      </select>
                    </div>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table id="ranking" class="table table-striped table-valign-middle">
                  <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Athlete</th>
                        <th>Award</th>
                        <th>Sport</th>
                        <th>Coach</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="5" style="text-align: center">No data found.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
        </section>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2021</strong>
    All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard.js"></script>

<script>
$(document).ready(function(){
      // $('#tournaments_id').change(function(){  
      //     if ($('#tournaments_id').val() == ''){
      //       var btn_action = 'tournament_clear';
      //       $.ajax({  
      //             url:"action.php",  
      //             method:"POST",  
      //             data:{ btn_action:btn_action},  
      //             success:function(data){  
      //               $('#tournament').html(data);  
      //             }  
      //       });  
      //     }
      //     var tournaments_id = $(this).val();  
      //     var btn_action = 'tournament_change';
      //     $.ajax({  
      //       url:"action.php",  
      //       method:"POST",  
      //       data:{tournaments_id:tournaments_id, btn_action:btn_action},  
      //       success:function(data){  
      //         $('#sports_id').html(data);  
      //       }  
      //     });  
      // });  

      // $('#sports_id').change(function(){  
      //     var sports_id = $(this).val();  
      //     var tournaments_id = $('#tournaments_id').val(); 
      //     var btn_action = 'sports_change';
      //     $.ajax({  
      //           url:"action.php",  
      //           method:"POST",  
      //           data:{sports_id:sports_id, tournaments_id:tournaments_id, btn_action:btn_action},  
      //           success:function(data){  
      //             $('#tournament').html(data);  
      //           }  
      //     });  
      // });   

      $('#ranking_tournament_id').change(function(){  
          var ranking_tournament_id = $(this).val();  
          var btn_action = 'ranking_change';
          $.ajax({  
            url:"action.php",  
            method:"POST",  
            data:{ranking_tournament_id:ranking_tournament_id, btn_action:btn_action},  
            success:function(data){  
              $('#ranking').html(data);  
            }  
          });  
      });  

 }); 
 
 $(function () {
    var tournamentsdataTable = $('#example1').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
        url:"fetch_data.php",
        type:"POST"
      },
      "columnDefs":[
        {
          "targets":[0],
          "orderable":false,
        },
      ],
      "pageLength": 10, 
    });
    // $(document).on('click', '.accept', function(){
    //   var id = $(this).attr('id');
    //   var sports_id = $('#sports_id').val();  
    //   var tournaments_id = $('#tournaments_id').val(); 
    //   var btn_action = 'accept';
    //   if(confirm("Are you sure you want to accept this athlete?"))
    //   {
    //     $.ajax({
    //       url:"action.php",
    //       method:"POST",
    //       data:{id:id, sports_id:sports_id, tournaments_id:tournaments_id, btn_action:btn_action},
    //       success:function(data)
    //       {
    //         $('#tournament').html(data);  
    //       }
    //     })
    //   }
    //   else
    //   {
    //     return false;
    //   }
    // });
    // $(document).on('click', '.decline', function(){
    //   var id = $(this).attr('id');
    //   var sports_id = $('#sports_id').val();  
    //   var tournaments_id = $('#tournaments_id').val(); 
    //   var btn_action = 'decline';
    //   if(confirm("Are you sure you want to decline this athlete?"))
    //   {
    //     $.ajax({
    //       url:"action.php",
    //       method:"POST",
    //       data:{id:id, sports_id:sports_id, tournaments_id:tournaments_id, btn_action:btn_action},
    //       success:function(data)
    //       {
    //         $('#tournament').html(data); 
    //       }
    //     })
    //   }
    //   else
    //   {
    //     return false;
    //   }
    // });
 });
</script>
</body>
</html>