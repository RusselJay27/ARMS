<?php
include('../../database_connection.php');
include('../../function.php');
if(!isset($_SESSION["user_type"]))
{
  header("location:../../../login.php");
}

if($_SESSION["athletes_fullname"] == '')
{
  header("location:../../athletes/");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $_SESSION['user_type']; ?> Portal | Athletes</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">
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
                src="../../../assets/yasdo_logo.png"
                alt="User profile picture"> 
      </li>
    </ul>

  </nav>

  <aside class="main-sidebar sidebar-light-warning elevation-4">
    <a href="#" class="brand-link navbar-warning">
      <img src="../../../dist/img/login_user.png" alt="User Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"> <?php echo $_SESSION["user_name"]; ?></span>
    </a>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="../../index.php" class="nav-link">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'Admin'){?>
            <li class="nav-item">
                <a href="../../schools/" class="nav-link">
                    <i class="far fa-building nav-icon"></i>
                    <p>Schools</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../../grade_level/" class="nav-link">
                    <i class="fa fa-book nav-icon"></i>
                    <p>Grade Level</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../../sports/" class="nav-link">
                    <i class="fa fa-basketball-ball nav-icon"></i>
                    <p>Sports</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../../tournaments/" class="nav-link">
                <i class="ion ion-stats-bars nav-icon"></i>
                    <p>Tournaments</p>
                </a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a href="../../coaches/" class="nav-link">
                    <i class="far fa-user nav-icon"></i>
                    <p>Coaches</p>
                </a>
            </li>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link active">
                    <i class="fa fa-child nav-icon"></i>
                <p>
                Athletes
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Achievements</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
                <a href="../../report/" class="nav-link">
                    <i class="fa fa-download nav-icon"></i>
                    <p>Report</p>
                </a>
            </li>
            <?php if ($_SESSION['user_type'] == 'Admin'){?>
            <li class="nav-item">
                <a href="../../users/" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Users</p>
                </a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a href="../../profile/" class="nav-link">
                    <i class="fas fa-user nav-icon"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../../../logout.php" class="nav-link">
                    <i class="fa fa-sign-out-alt nav-icon"></i>
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
            <h1>Atheltes : <?php echo $_SESSION['athletes_fullname']; ?></h1> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#achievementModal" class="btn btn-warning">Add</button>   
              &nbsp
              <button type="button" name="back" id="back_button" class="btn btn-info">Back</button>    
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
              <div class="card-body">
                <table id="dataTables" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Tournament</th>
                    <th>Sport</th>
                    <th>Coach</th>
                    <th>Award</th>
                    <th>Delete</th>
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
  </div>
  <footer class="main-footer">
    <strong>Copyright &copy; 2021</strong>
    All rights reserved.
  </footer>

</div>

  <div id="achievementModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="achievements_form">
    			<div class="modal-content">
    				<div class="modal-header">
						<h4 class="modal-title"><i class="fa fa-plus"></i></h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">

            <div class="form-group">
    						<select name="tournaments_id" id="tournaments_id" class="form-control" required>
								<option value="">Select Tournament *</option>
                  <?php echo fill_tournaments_list($connect) ?> 
							  </select>
    					</div>
    					<div class="form-group">
    						<select name="sports_id" id="sports_id" class="form-control" required>
								<option value="">Select Sport *</option>
							  </select>
    					</div>
    					<div class="form-group">
    						<select name="award" id="award" class="form-control" required>
								<option value="">Select Award *</option>
								<option value="3">Gold Medalist</option>
								<option value="2">Silver Medalist</option>
								<option value="1">Bronze Medalist</option>
							  </select>
    					</div>

    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="id" id="id"/>
    					<input type="hidden" name="btn_action" id="btn_action"/>
    					<input type="submit" name="action" id="action" class="btn btn-warning" value="Add" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
  </div>

<!-- jQuery -->
<script src="../../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../../plugins/jszip/jszip.min.js"></script>
<script src="../../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>

$(document).ready(function(){
      $('#tournaments_id').change(function(){  
           var tournaments_id = $(this).val();  
            var btn_action = 'change';
           $.ajax({  
                url:"action.php",  
                method:"POST",  
                data:{tournaments_id:tournaments_id, btn_action:btn_action},  
                success:function(data){  
                  $('#sports_id').html(data);  
                }  
           });  
      });  
 });  

  $(function () {
    $('#back_button').click(function(){
      var btn_action = 'back';
      $.ajax({
        url:"action.php",
          method:"POST",
          data:{btn_action:btn_action},
        success:function(data)
        {
          window.location.href = "../../athletes/";
        }
      })
    });

    $('#add_button').click(function(){
      $('#achievements_form')[0].reset();
      $('.modal-title').html("<i class='fa fa-plus'></i> Add Achievement");
      $('#action').val('Add');
      $('#btn_action').val('Add');
    });
    
    $(document).on('submit','#achievements_form', function(event){
      event.preventDefault();
      $('#action').attr('disabled','disabled');
      var form_data = $(this).serialize();
      $.ajax({
        url:"action.php",
        method:"POST",
        data:form_data,
        success:function(data)
        {
          $('#achievements_form')[0].reset();
          $('#achievementModal').modal('hide');
          $('#alert_action').fadeIn().html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>'+data+'</div>');
          $('#action').attr('disabled', false);
          dataTable.ajax.reload();
        }
      })
    });

    $(document).on('click', '.delete', function(){
      var id = $(this).attr('id');
      var btn_action = 'delete';
      if(confirm("Are you sure you want to delete?"))
      {
        $.ajax({
          url:"action.php",
          method:"POST",
          data:{id:id, btn_action:btn_action},
          success:function(data)
          {
            $('#alert_action').fadeIn().html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>'+data+'</div>');
            dataTable.ajax.reload();
          }
        })
      }
      else
      {
        return false;
      }
    });
    
    var dataTable = $('#dataTables').DataTable({
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
          "targets":[0, 5],
          "orderable":false,
        },
      ],
      "pageLength": 10, 
    });

  });
</script>
</body>
</html>