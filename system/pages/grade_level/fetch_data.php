<?php

//fetch_data.php

include('../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT * FROM grade_level where";

if(isset($_POST["search"]["value"]))
{
	$query .= '(level_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR level_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY level_id DESC ';
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
	$sub_array[] = $row['level_id'];
	$sub_array[] = $row['level_name'];
	$sub_array[] = $row['date_created'];

	if($row['level_status'] == 'Active')
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["level_id"].'" class="btn btn-success  btn-flat btn-xs status" data-status="'.$row["level_status"].'">Active</button>';
	}
	else
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["level_id"].'" class="btn btn-info  btn-flat btn-xs status" data-status="'.$row["level_status"].'">Inactive</button>';
	}
	$sub_array[] = '<button type="button" name="update" id="'.$row["level_id"].'" class="btn btn-warning  btn-flat btn-xs update">Update</button>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["level_id"].'" class="btn btn-danger  btn-flat btn-xs delete">Delete</button>';
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
	$statement = $connect->prepare("SELECT * FROM grade_level");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>