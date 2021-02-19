<?php

include('pages/database_connection.php');
//include('pages/function.php');

session_destroy();
header("location: ../system/login.php");
?>