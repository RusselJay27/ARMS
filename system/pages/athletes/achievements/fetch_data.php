<?php

//fetch_data.php

include('../../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT achievements.*, sports.sports_name, sports.category, tournaments.tournaments_name
FROM achievements 
INNER JOIN sports ON achievements.sports_id = sports.sports_id 
INNER JOIN tournaments ON achievements.tournaments_id = tournaments.tournaments_id 
where achievements.athletes_id = '".$_SESSION['athletes_id']."' AND ";

//INNER JOIN coaches ON sports.coaches_id = coaches.coaches_id  , coaches.coaches_last, coaches.coaches_first, coaches.coaches_mi
	//$sub_array[] = $row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.';

if(isset($_POST["search"]["value"]))
{
	$query .= '(sports.sports_name  LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR sports.category LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR achievements.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY achievements.id DESC ';
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
	$award ='';
	$sub_array = array();
	$sub_array[] = $row['id'];
	$sub_array[] = $row['tournaments_name'];
	$sub_array[] = $row['sports_name'].' - '.$row['category'];
	$sub_array[] = $row['category'];

	if ($row['award'] == '3'){
		$award = 'Gold';
	}
	if ($row['award'] == '2'){
		$award = 'Silver';
	}
	if ($row['award'] == '1'){
		$award = 'Bronze';
	}
	$sub_array[] = $award;

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