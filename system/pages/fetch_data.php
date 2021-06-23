<?php

//fetch_data.php

include('database_connection.php');

$query = '';

$output = array();

//$query .= "SELECT * FROM tournaments where";
$query .= "SELECT tournaments.tournaments_name, tournaments.date, sports.sports_name, sports.category, athletes.athletes_last, athletes.athletes_first, 
athletes.athletes_mi, coaches.coaches_last, coaches.coaches_first, coaches.coaches_mi,
(SELECT award FROM achievements where athletes_id = tournament_athletes.athletes_id) as award,
(SELECT tournaments_name FROM tournaments where tournaments_id = (SELECT tournaments_id FROM achievements where athletes_id = tournament_athletes.athletes_id)) as tournament,
(SELECT CONCAT(sports_name, ' - ',category) FROM sports where sports_id = (SELECT sports_id FROM achievements where  athletes_id = tournament_athletes.athletes_id) ) as sports 
FROM tournament_athletes
INNER JOIN tournaments ON tournament_athletes.tournaments_id = tournaments.tournaments_id
INNER JOIN sports ON tournament_athletes.sports_id = sports.sports_id
INNER JOIN athletes ON tournament_athletes.athletes_id = athletes.athletes_id
INNER JOIN athlete_sports ON tournament_athletes.sports_id = athlete_sports.sports_id
INNER JOIN coaches ON athlete_sports.coaches_id = coaches.coaches_id WHERE tournaments.tournaments_status = 'Inactive' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(tournaments.tournaments_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR sports.sports_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR sports.category LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.athletes_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR athletes.athletes_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.coaches_last LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR coaches.coaches_first LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR tournaments.date LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY tournaments.tournaments_id DESC ';
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
	$award = '';
	$sub_array = array();
	if ($row['award'] == '3'){
		$award = 'Gold'." = ".$row['tournament']." / ".$row['sports'];
	}
	if ($row['award'] == '2'){
		$award = 'Silver'." = ".$row['tournament']." / ".$row['sports'];
	}
	if ($row['award'] == '1'){
		$award = 'Bronze'." = ".$row['tournament']." / ".$row['sports'];
	}
	if ($row['award'] == null){
		$award = 'No award yet.';
	}
	$sub_array[] = $award;
	$sub_array[] = $row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.';
	$sub_array[] = $row['athletes_last'].', '.$row['athletes_first'].' '.$row['athletes_mi'].'.';
	$sub_array[] = $row['sports_name']." - ".$row['category'];
	$sub_array[] = $row['tournaments_name'];
	$sub_array[] = $row['date'];
 	
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
	$statement = $connect->prepare("SELECT tournaments.tournaments_name, tournaments.date, sports.sports_name, sports.category, athletes.athletes_last, athletes.athletes_first, 
	athletes.athletes_mi, coaches.coaches_last, coaches.coaches_first, coaches.coaches_mi FROM tournament_athletes
	INNER JOIN tournaments ON tournament_athletes.tournaments_id = tournaments.tournaments_id
	INNER JOIN sports ON tournament_athletes.sports_id = sports.sports_id
	INNER JOIN athletes ON tournament_athletes.athletes_id = athletes.athletes_id
	INNER JOIN athlete_sports ON tournament_athletes.sports_id = athlete_sports.sports_id
	INNER JOIN coaches ON athlete_sports.coaches_id = coaches.coaches_id");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>