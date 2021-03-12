<?php

//fetch_data.php

include('../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT * FROM coaches where ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(coaches_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR gender LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR contact LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR email LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR birthdate LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY coaches_id DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	//date in mm/dd/yyyy format; or it can be in other formats as well
	$birthDate = $row['birthdate'];
	//explode the date to get month, day and year
	$birthDate = explode("/", $birthDate);
	//get age from date or birthdate
	$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
	? ((date("Y") - $birthDate[2]) - 1)
	: (date("Y") - $birthDate[2])); //$age

	$status = '';
	$sub_array = array();
	$sub_array[] = $row['coaches_id'];
	$sub_array[] = '<button type="button" name="sports" id="'.$row["coaches_id"].'" class="btn btn-primary btn-flat btn-xs sports">View</button>';
	$sub_array[] = $row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.';
	$sub_array[] = $row['gender'];
	$sub_array[] = $age;
	$sub_array[] = $row['birthdate'];;
	$sub_array[] = $row['address'];	
	$sub_array[] = $row['contact'];	
	$sub_array[] = $row['email'];	
	$sub_array[] = $row['date_created'];

	if($row['coaches_status'] == 'Active')
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["coaches_id"].'" class="btn btn-success  btn-flat btn-xs status" data-status="'.$row["coaches_status"].'">Active</button>';
	}
	else
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["coaches_id"].'" class="btn btn-info  btn-flat btn-xs status" data-status="'.$row["coaches_status"].'">Inactive</button>';
	}
	$sub_array[] = '<button type="button" name="update" id="'.$row["coaches_id"].'" class="btn btn-warning  btn-flat btn-xs update">Update</button>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["coaches_id"].'" class="btn btn-danger  btn-flat btn-xs delete">Delete</button>';
	$data[] = $sub_array;
}

$output = array(
	"draw"			=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"				=>	$data
);

function get_total_all_records($connect)
{
	$statement = $connect->prepare("SELECT * FROM coaches");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>