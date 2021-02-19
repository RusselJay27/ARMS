<?php

//fetch_data.php

include('../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT * FROM schools where";

if(isset($_POST["search"]["value"]))
{
	$query .= '(school_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR details LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR school_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY school_id DESC ';
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
	$sub_array[] = $row['school_id'];
	$sub_array[] = $row['school_name'];
	$sub_array[] = $row['details'];
	$sub_array[] = $row['address'];
	$sub_array[] = $row['date_created'];

	if($row['school_status'] == 'Active')
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["school_id"].'" class="btn btn-success  btn-flat btn-xs status" data-status="'.$row["school_status"].'">Active</button>';
	}
	else
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["school_id"].'" class="btn btn-info  btn-flat btn-xs status" data-status="'.$row["school_status"].'">Inactive</button>';
	}
	$sub_array[] = '<button type="button" name="update" id="'.$row["school_id"].'" class="btn btn-warning  btn-flat btn-xs update">Update</button>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["school_id"].'" class="btn btn-danger  btn-flat btn-xs delete">Delete</button>';
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
	$statement = $connect->prepare("SELECT * FROM schools");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>