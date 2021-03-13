<?php

//fetch_data.php

include('../../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT athlete_sports.*, sports.sports_name, sports.category, coaches.coaches_last, coaches.coaches_first, coaches.coaches_mi
FROM athlete_sports 
INNER JOIN sports ON athlete_sports.sports_id = sports.sports_id 
INNER JOIN coaches ON athlete_sports.coaches_id = coaches.coaches_id 
where athlete_sports.athletes_id = '".$_SESSION['athletes_id']."' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(sports.sports_name  LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR sports.category LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athlete_sports.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY athlete_sports.id DESC ';
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
	$sub_array = array();
	$sub_array[] = $row['id'];
	$sub_array[] = $row['category'];
	$sub_array[] = $row['sports_name'];
	$sub_array[] = $row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger  btn-flat btn-xs delete">Delete</button>';
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
	$statement = $connect->prepare("SELECT * FROM coach_sports 
	INNER JOIN sports ON coach_sports.sports_id = sports.sports_id 
	where coach_sports.coaches_id = '".$_SESSION['coaches_id']."' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>