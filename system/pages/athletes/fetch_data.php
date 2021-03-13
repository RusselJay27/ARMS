<?php

//fetch_data.php

include('../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT athletes.*,  grade_level.level_name, schools.school_name
FROM athletes 
INNER JOIN grade_level ON athletes.level_id = grade_level.level_id 
INNER JOIN schools ON athletes.school_id = schools.school_id 
where";

if(isset($_POST["search"]["value"]))
{
	$query .= '(athletes.athletes_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.athletes_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.gender LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.contact LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.email LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.birthdate LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.height LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.weight LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.scholar LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.varsity LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.class_a LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR schools.school_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY athletes.athletes_id DESC ';
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
	$sub_array[] = $row['athletes_id'];
	$sub_array[] = '<button type="button" name="sports" id="'.$row["athletes_id"].'" class="btn btn-primary  btn-flat btn-xs sports">View</button>';
	$sub_array[] = $row['athletes_last'].', '.$row['athletes_first'].' '.$row['athletes_mi'].'.';
	$sub_array[] = $row['level_name'];
	$sub_array[] = $row['gender'];
	$sub_array[] = $row['school_name'];

	if($row['athletes_status'] == 'Active')
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["athletes_id"].'" class="btn btn-success  btn-flat btn-xs status" data-status="'.$row["athletes_status"].'">Active</button>';
	}
	else
	{
	$sub_array[] = '<button type="button" name="status" id="'.$row["athletes_id"].'" class="btn btn-info  btn-flat btn-xs status" data-status="'.$row["athletes_status"].'">Inactive</button>';
	}
	$sub_array[] = '<button type="button" name="update" id="'.$row["athletes_id"].'" class="btn btn-warning  btn-flat btn-xs update">Update</button>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["athletes_id"].'" class="btn btn-danger  btn-flat btn-xs delete">Delete</button>';
	$sub_array[] = '<button type="button" name="view" id="'.$row["athletes_id"].'" class="btn btn-primary  btn-flat btn-xs view">View</button>';
	$sub_array[] = '<button type="button" name="view" id="'.$row["athletes_id"].'" class="btn btn-info  btn-flat btn-xs view">View</button>';
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
	$statement = $connect->prepare("SELECT * FROM athletes INNER JOIN grade_level ON athletes.level_id = grade_level.level_id  INNER JOIN schools ON athletes.school_id = schools.school_id ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>