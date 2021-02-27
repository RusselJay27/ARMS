<?php

//fetch_data.php

include('../../database_connection.php');

$query = '';

$output = array();

$query .= "SELECT tournament_sports.*, sports.sports_name FROM tournament_sports 
INNER JOIN sports ON tournament_sports.sports_id = sports.sports_id 
where tournament_sports.tournaments_id = '".$_SESSION['tournaments_id']."'AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(sports.sports_name  LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR tournament_sports.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY tournament_sports.tournament_sports_id DESC ';
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
	$sub_array[] = $row['tournament_sports_id'];
	$sub_array[] = $row['sports_name'];

	$sub_array[] = '<button type="button" name="update" id="'.$row["tournament_sports_id"].'" class="btn btn-warning  btn-flat btn-xs update">Update</button>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["tournament_sports_id"].'" class="btn btn-danger  btn-flat btn-xs delete">Delete</button>';
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
	$statement = $connect->prepare("SELECT * FROM tournament_sports 
	INNER JOIN sports ON tournament_sports.sports_id = sports.sports_id ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>