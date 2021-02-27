<?php 

include('../database_connection.php');
//include('function.php');
$query = "UPDATE user_account SET 
user_name = '".$_POST["user_name"]."',
user_password = '".password_hash($_POST["user_password"], PASSWORD_DEFAULT)."' 
WHERE user_id = '".$_SESSION["user_id"]."' ";
$statement = $connect->prepare($query);
$result = $statement->execute();
//$result = $statement->fetchAll();
if(isset($result))
{
	echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i>Profile Saved</div>';
}
?>