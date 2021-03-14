<?php

//fetch_data.php

include('../../../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT tournament_athletes.*, athletes.*, coaches.* FROM tournament_athletes
INNER JOIN athletes ON tournament_athletes.athletes_id = athletes.athletes_id
INNER JOIN athlete_sports ON athletes.athletes_id = athlete_sports.athletes_id 
AND tournament_athletes.sports_id = athlete_sports.sports_id
INNER JOIN coaches ON athlete_sports.coaches_id = coaches.coaches_id 
where tournament_athletes.sports_id = '".$_SESSION["sports_id"]."' AND 
tournament_athletes.tournaments_id = '".$_SESSION["tournaments_id"]."' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(athletes.athletes_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.athletes_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.coaches_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.coaches_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR tournament_athletes.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY tournament_athletes.id DESC ';
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

	$sub_array[] = $row["athletes_last"].', '.$row["athletes_first"].' '.$row["athletes_mi"].'.';
	$sub_array[] = $row["coaches_last"].', '.$row["coaches_first"].' '.$row["coaches_mi"].'.';

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
	$statement = $connect->prepare("SELECT tournament_athletes.*, athletes.*, coaches.* FROM tournament_athletes
	INNER JOIN athletes ON tournament_athletes.athletes_id = athletes.athletes_id
	INNER JOIN athlete_sports ON athletes.athletes_id = athlete_sports.athletes_id 
	AND tournament_athletes.sports_id = athlete_sports.sports_id
	INNER JOIN coaches ON athlete_sports.coaches_id = coaches.coaches_id 
	where tournament_athletes.sports_id = '".$_SESSION["sports_id"]."' AND 
	tournament_athletes.tournaments_id = '".$_SESSION["tournaments_id"]."' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>