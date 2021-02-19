<?php

//fetch_data.php

include('../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT coaches.coaches_id, coaches.coaches_last, coaches.coaches_first, coaches.coaches_mi, coaches.address,
coaches.gender, coaches.contact, coaches.email, coaches.birthdate, coaches.coaches_status, coaches.date_created, sports.sports_name
FROM coaches INNER JOIN sports ON coaches.sports_id = sports.sports_id where";

if(isset($_POST["search"]["value"]))
{
	$query .= '(coaches.coaches_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.coaches_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.gender LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.contact LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.email LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.birthdate LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR sports.sports_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY coaches.coaches_id DESC ';
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
	$status = '';
	$sub_array = array();
	$sub_array[] = $row['coaches_id'];
	$sub_array[] = $row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.';
	$sub_array[] = $row['sports_name'];
	$sub_array[] = $row['gender'];
	$sub_array[] = $row['birthdate'];
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