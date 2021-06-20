<?php
include('../database_connection.php');
include('../function.php');
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
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- Croppie JS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" rel="stylesheet" type="text/css">

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
            <?php if ($_SESSION['user_type'] !== 'Coach'){?>
            <li class="nav-item">
                <a href="./../index.php" class="nav-link">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>
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
            <li class="nav-item">
                <a href="./../coaches/" class="nav-link">
                    <i class="far fa-user nav-icon"></i>
                    <p>Coaches</p>
                </a>
            </li>
            <?php }?>
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fa fa-child nav-icon"></i>
                    <p>Athletes</p>
                </a>
            </li>
            <?php if ($_SESSION['user_type'] != 'Coach'){?>
            <li class="nav-item">
                <a href="./../report/" class="nav-link">
                    <i class="fa fa-download nav-icon"></i>
                    <p>Report</p>
                </a>
            </li>
            <?php }?>
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Athletes</h1>
          </div>

          <?php if($_SESSION["user_type"] != 'Coach') { ?>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#athletesModal" class="btn btn-warning">Add</button>   
            </ol>
          </div>
          <?php } ?>
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
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <?php if($_SESSION["user_type"] != 'Coach') { ?>
                      <th>Sport</th>
                      <th>Achievements</th>
                      <th>Fullname</th>
                      <th>Grade Level</th>
                      <th>Gender</th>
                      <th>School</th>
                      <th>Status</th>
                      <th>Update</th>
                      <th>Delete</th>
                    <?php } else { ?>
                      <th>Fullname</th>
                      <th>Grade Level</th>
                      <th>Gender</th>
                      <th>School</th>
                      <th>Update</th>
                    <?php } ?>
                    <th>More</th>
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

  <div id="athletesModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="athletes_form" enctype="multipart/form-data">
    			<div class="modal-content">
    				<div class="modal-header">
						<h4 class="modal-title"><i class="fa fa-plus"></i></h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">

                <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <img src="../../assets/img/default-placeholder.jpg" alt="Default Avatar" class="img-thumbnail" >
                      </div>  
                      <div class="form-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="file" name="file" accept="image/x-png,image/jpeg" onchange="readURL(this);">
                          <label class="custom-file-label" for="file" id="files" name="files">Choose file</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="athletes_last" id="athletes_last" class="form-control" maxlength="50" 
                        style="text-transform: capitalize;" required />
                      </div>
                      <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="athletes_first" id="athletes_first" class="form-control" maxlength="50" 
                        style="text-transform: capitalize;" required />
                      </div>
                      <div class="form-group">
                        <label>M.I. *</label>
                        <input type="text" name="athletes_mi" id="athletes_mi" class="form-control"  maxlength="1" 
                        style="text-transform:uppercase"  required/>
                      </div>
                    </div>
                </div>

                  <div class="row">
                    <div class="col-4">

                      <div class="form-group">
                        <label>Birthdate *</label>
                          <div class="input-group date" id="birthdates" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#birthdates" name="birthdate" id="birthdate" required/>
                              <div class="input-group-append" data-target="#birthdates" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Height(cm) *</label>
                        <input type="number" name="height" id="height" class="form-control"  maxlength="3" 
                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required />
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Weight(kg) *</label>
                        <input type="number" name="weight" id="weight" class="form-control"  maxlength="3" 
                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required />
                      </div>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <select name="level_id" id="level_id" class="form-control" required>
                            <option value="">Select Grade Level *</option>
                            <?php echo fill_level_list($connect) ?> 
                          </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="">Select Gender *</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                      </div>
                    </div>
                  </div>  
                  <div class="row">
                    <div class="col-8">
                      <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" id="email" class="form-control" maxlength="50" required />
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Contact *</label>
                        <input type="number" name="contact" id="contact" class="form-control"  maxlength="11" 
                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required />
                      </div>
                    </div>
                  </div> 

                  <div class="form-group">
                    <label>Enter Address *</label>
                    <input type="text" name="street" id="street" class="form-control" placeholder="House No. / Street / Village or Subdivision" maxlength="50" 
                        style="text-transform: capitalize;" required />
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="barangay" id="barangay" class="form-control" placeholder="Barangay" maxlength="50" 
                        style="text-transform: capitalize;" required />
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="city" id="city" class="form-control" placeholder="City / Municipality" maxlength="50" 
                        style="text-transform: capitalize;" required />
                      </div>
                    </div>
                  </div> 

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <select name="school_id" id="school_id" class="form-control" required>
                            <option value="">Select School *</option>
                            <?php echo fill_schools_list($connect) ?> 
                          </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <select name="scholar" id="scholar" class="form-control" required>
                            <option value="">MSP Scholar *</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          </select>
                      </div>
                    </div>
                  </div>  

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <select name="varsity" id="varsity" class="form-control" required>
                            <option value="">School Varsity *</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <select name="class_a" id="class_a" class="form-control" required>
                            <option value="">Class-A Athlete *</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          </select>
                      </div>
                    </div>
                  </div>

    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="athletes_id" id="athletes_id"/>
    					<input type="hidden" name="btn_action" id="btn_action"/>
    					<input type="submit" name="action" id="action" class="btn btn-warning" value="Add" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
  </div>
 
	        <div id="athletesDetailsModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="athlete_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa fa-info-circle"></i> Athletes Information</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <Div id="athletes_details"></Div>
                        </div>
                        <div class="modal-footer">
                            
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
        		<h4 class="modal-title">Crop Athlete Image</h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>
      		<div class="modal-body">
        		<div class="row">
                    <div class="col-md-8 text-center">
                        <div id="image_demo" style="width:350px; margin-top:30px"></div>
                    </div>
			    </div>
      		</div>
      		<div class="modal-footer">
                <button class="btn btn-success crop_image">Crop Athlete Image</button>
      		</div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
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
<!-- bs-custom-file-input -->
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"></script> 
<!-- Page specific script -->
<script>
  function readURL(input) {
       if (input.files && input.files[0]) {
           let reader = new FileReader();
           reader.onload = function (e) {
              $('.img-thumbnail')
                .attr('src', e.target.result);
           };
           reader.readAsDataURL(input.files[0]);
       }
    }

  $(function () {

    $('#add_button').click(function(){
      $('#athletes_form')[0].reset();
      $('.img-thumbnail')
            .attr('src', '../../assets/img/default-placeholder.jpg');
      $('.modal-title').html("<i class='fa fa-plus'></i> Add Athlete");
      $('#action').val('Add');
      $('#btn_action').val('Add');
    });
    
    $(document).on('submit','#athletes_form', function(event){
      event.preventDefault();
      $('#action').attr('disabled','disabled');
      //var form_data = $(this).serialize();
      $.ajax({
        url:"action.php",
        method:"POST",
        //data:form_data,
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        success:function(data)
        {
          $('#athletes_form')[0].reset();
          $('#athletesModal').modal('hide');
          $('#alert_action').fadeIn().html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>'+data+'</div>');
          $('#action').attr('disabled', false);
          athletesdataTable.ajax.reload();
        }
      })
    });
    
    $(document).on('click', '.status', function(){
      var athletes_id = $(this).attr('id');
      var status = $(this).data("status");
      var btn_action = 'status';
      if(confirm("Are you sure you want to change status?"))
      {
        $.ajax({
          url:"action.php",
          method:"POST",
          data:{athletes_id:athletes_id, status:status, btn_action:btn_action},
          success:function(data)
          {
            $('#alert_action').fadeIn().html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>'+data+'</div>');
            athletesdataTable.ajax.reload();
          }
        })
      }
      else
      {
        return false;
      }
    });

    $(document).on('click', '.delete', function(){
      var athletes_id = $(this).attr('id');
      var btn_action = 'delete';
      if(confirm("Are you sure you want to delete?"))
      {
        $.ajax({
          url:"action.php",
          method:"POST",
          data:{athletes_id:athletes_id, btn_action:btn_action},
          success:function(data)
          {
            $('#alert_action').fadeIn().html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-info"></i>'+data+'</div>');
            athletesdataTable.ajax.reload();
          }
        })
      }
      else
      {
        return false;
      }
    });

    $(document).on('click', '.update', function(){
      var athletes_id = $(this).attr("id");
      var btn_action = 'fetch_single';
      $.ajax({
        url:"action.php",
        method:"POST",
        data:{athletes_id:athletes_id, btn_action:btn_action},
        dataType:"json",
        success:function(data)
        {
          $('#athletesModal').modal('show');
          $('#athletes_last').val(data.athletes_last);
          $('#athletes_first').val(data.athletes_first);
          $('#athletes_mi').val(data.athletes_mi);
          $('#coaches_id').val(data.coaches_id);
          $('#birthdate').val(data.birthdate);
          $('#street').val(data.street);
          $('#barangay').val(data.barangay);
          $('#city').val(data.city);
          $('#gender').val(data.gender);
          $('#contact').val(data.contact);
          $('#email').val(data.email);
          $('#height').val(data.height);
          $('#weight').val(data.weight);
          $('#level_id').val(data.level_id);
          $('#school_id').val(data.school_id);
          $('#scholar').val(data.scholar);
          $('#varsity').val(data.varsity);
          $('#class_a').val(data.class_a);
          $('.img-thumbnail')
                   .attr('src', data.image);
          $('.modal-title').html("<i class='fa fa-edit'></i> Edit Athlete");
          $('#athletes_id').val(athletes_id);
          $('#action').val('Edit');
          $('#btn_action').val("Edit");
        }
      })
    });

    $(document).on('click', '.view', function(){
        var athletes_id = $(this).attr("id");
        var btn_action = 'athletes_details';
        $.ajax({
            url:"action.php",
            method:"POST",
            data:{athletes_id:athletes_id, btn_action:btn_action},
            success:function(data){
                $('#athletesDetailsModal').modal('show');
                $('#athletes_details').html(data);
              $('.modal-title').html("<i class='fa fa-info-circle'></i> Athletes Information");
            }
        })
    });
    
    $(document).on('click', '.sports', function(){
      var athletes_id = $(this).attr("id");
      var btn_action = 'fetch_sports';
      $.ajax({
        url:"action.php",
        method:"POST",
        data:{athletes_id:athletes_id, btn_action:btn_action},
        success:function(data)
        {
          window.location.href = "./sports/";
        }
      })
    });
    
    $(document).on('click', '.achievements', function(){
      var athletes_id = $(this).attr("id");
      var btn_action = 'fetch_sports';
      $.ajax({
        url:"action.php",
        method:"POST",
        data:{athletes_id:athletes_id, btn_action:btn_action},
        success:function(data)
        {
          window.location.href = "./achievements/";
        }
      })
    });
    
    var athletesdataTable = $('#example1').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
        url:"fetch_data.php",
        type:"POST"
      },
      
      <?php if($_SESSION["user_type"] != 'Coach') { ?>
      "columnDefs":[
        {
          "targets":[0,1,2,7,8,9,10],
          "orderable":false,
        },
      <?php } else { ?>
      "columnDefs":[
        {
          "targets":[0,5,6],
          "orderable":false,
        },
      <?php } ?>
      
      ],

      "pageLength": 10, 
    });
    //Date range picker
    $('#birthdates').datetimepicker({
        format: 'L'
    });
    
    bsCustomFileInput.init();

  });

  $(document).ready(function(){
    var filename = null;

    // $('#uploadimageModal').on('hidden.bs.modal', function (e) {
    //         if ($('#new_image').val() == ''){
    //             $('#upload_image').val("");
    //             $("#upload_image_name").empty();
    //             $("#upload_image_name").append("Choose file");
    //         }
    //         $('#athletesModal').modal('show');
    //     });

    // $image_crop = $('#image_demo').croppie({
    //         enableExif: true,
    //         viewport: {
    //         width:250,
    //         height:250,
    //         type:'square'
    //         },
    //         boundary:{
    //         width:350,
    //         height:350
    //         }
    //     });

    //   $('#upload_image').on('change', function(){
    //     var reader = new FileReader();
    //     reader.onload = function (event) {
    //         $image_crop.croppie('bind', {
    //             url: event.target.result
    //         }).then(function(){
    //         });
    //     }
    //     reader.readAsDataURL(this.files[0]);
    //     filename = this.files[0].name;
    //     $('.modal-title').html("Crop Athlete Image");
    //     $('#uploadimageModal').modal('toggle');
    //     $('#athletesModal').modal('toggle');
    // });

    // $('.crop_image').click(function(event){
    //         $image_crop.croppie('result', {
    //         type: 'canvas',
    //         size: 'viewport'
    //         }).then(function(response){
    //             $('#new_image').val(response);
    //             $("#upload_image_name").empty();
    //             $("#upload_image_name").append(filename);
    //             $('#athletesModal').modal('toggle');
    //             $('#uploadimageModal').modal('toggle');
    //         })
    //     });
  });
</script>


</body>
</html>