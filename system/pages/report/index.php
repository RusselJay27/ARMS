<?php
include('../database_connection.php');
include('../function.php');
if(!isset($_SESSION["user_type"]))
{
  header("location:../../login.php");
}

function fetch_data($connect,$tournaments_id)  
 { 
      $output = '';
      $query = " SELECT
                tournament_athletes.*, 
                athletes.athletes_last, 
                athletes.athletes_first, 
                athletes.athletes_mi, 
                sports.category, 
                sports.sports_name, 
                coaches.coaches_last, 
                coaches.coaches_first, 
                coaches.coaches_mi,
                (SELECT award from achievements WHERE athletes_id = tournament_athletes.athletes_id AND tournaments_id = tournament_athletes.tournaments_id AND sports_id = tournament_athletes.sports_id ) as award
            FROM
                tournament_athletes
            INNER JOIN athletes ON tournament_athletes.athletes_id = athletes.athletes_id
            INNER JOIN sports ON tournament_athletes.sports_id = sports.sports_id
            INNER JOIN coach_sports ON sports.sports_id = coach_sports.sports_id
            INNER JOIN coaches ON coach_sports.coaches_id = coaches.coaches_id
            WHERE
                tournament_athletes.tournaments_id = :tournaments_id
            ORDER BY award DESC ";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                    ':tournaments_id'	=> $tournaments_id
                )
            );
            $result = $statement->fetchAll();

            foreach($result as $row)
            {
              $award = '';
              if ($row['award'] == '3'){
                $award = 'Gold';
              }
              if ($row['award'] == '2'){
                $award = 'Silver';
              }
              if ($row['award'] == '1'){
                $award = 'Bronze';
              }
              $output .= '
                <tr>
                  <td >'.$row['athletes_last'].', '.$row['athletes_first'].' '.$row['athletes_mi'].'.'.'</td>
                  <td >'.$row["sports_name"].' - '.$row["category"].'</td>
                  <td >'.$row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.'.'</td>
                  <td >'.$award.'</td>
                  <td >'.$row['date_created'].'</td>
                </tr>
                ';
            }
        return $output;
 }  
 if(isset($_POST["pdf_button"]))  
 { 
      $tournaments_id = $_POST['tournaments_id'];
      require_once('../tcpdf/tcpdf.php');  
      $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      $obj_pdf->SetCreator(PDF_CREATOR);  
      $obj_pdf->SetTitle("Report of Athletes per Tournament");  
      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(false);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      $obj_pdf->SetFont('helvetica', '', 12);  
      $obj_pdf->AddPage();  
      $content = '';  
      $content .= '  
      <h3 align="center">Report of Athletes per Tournament</h3><br /><br />  
      <table border="1" cellspacing="0" cellpadding="2">  
           <tr> 
              <th width="25%">Athlete</th>
              <th width="25%">Sport</th>
              <th width="25%">Coach</th>
              <th width="10%">Award</th>
              <th width="15%">Date Event</th>
           </tr>  
      ';  
      $content .= fetch_data($connect,$tournaments_id);  
      $content .= '</table>';  
      $obj_pdf->writeHTML($content);  
      $obj_pdf->Output('Report of Athletes per Tournament.pdf', 'I');
  //echo $data;
 }  
 
$_SESSION['tournaments_id'] ='';
$_SESSION['tournaments_name'] ='';
$_SESSION['coaches_id'] = '';
$_SESSION['coaches_fullname'] = '';
$_SESSION['athletes_id'] ='';
$_SESSION['athletes_fullname'] ='';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $_SESSION['user_type']; ?> Portal | Report</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                <a href="#" class="nav-link active">
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
                <a href="./../profile/" class="nav-link">
                    <i class="fas fa-user nav-icon"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../../logout.php" class="nav-link">
                    <i class="fa fa-sign-out-alt nav-icon"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <form method="post">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <button type="submit" name="pdf_button" id="pdf_button" class="btn btn-warning">Print PDF</button> 
            </ol>
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
              <div class="card-header">
                <h3 class="card-title">Report of Athletes per Tournament</h3>
                <div class="card-tools">
                    <div class="col-12">
                      <div class="form-group">
                        <select name="tournaments_id" id="tournaments_id" class="form-control" required>
                          <option value="">Select Tournament</option>
                          <?php echo fill_tournaments_ranking_list($connect) ?> 
                        </select>
                      </div>
                    </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="report" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Athlete</th>
                      <th>Sport</th>
                      <th>Coach</th>
                      <th>Award</th>
                      <th>Date Event</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan='5' style='text-align: center'>Please select tournament above.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </section>
    </form>
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
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
$(document).ready(function(){
      $('#tournaments_id').change(function(){  
          var tournaments_id = $(this).val();  
          var btn_action = 'tournament_change';
          $.ajax({  
                url:"action.php",  
                method:"POST",  
                data:{ tournaments_id:tournaments_id, btn_action:btn_action},  
                success:function(data){  
                  $('#report').html(data);  
                }  
          });  
      }); 

 }); 

</script>
</body>
</html>