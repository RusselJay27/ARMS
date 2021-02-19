<body class="hold-transition sidebar-mini layout-fixed text-sm">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-light navbar-warning border-bottom-0 accent-warning">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link text-center">
      <span class="brand-text font-weight-light text-center"><?php echo $_SESSION["user_name"]; ?></span>
    </a>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./school.php" class="nav-link">
                    <i class="far fa-building nav-icon"></i>
                    <p>Schools</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Grade Level</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sports</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tournaments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Achievements</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Report</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>User</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="fas fa-user nav-icon"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="./index.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>