<?php

//action.php

include('../../database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'back')
	{
        $_SESSION['tournaments_id'] ='';
        $_SESSION['tournaments_name'] ='';
	}

	if($_POST['btn_action'] == 'Add')
	{
		$sports_id = '';
		$query2 = "SELECT * FROM tournament_sports WHERE sports_id = :sports_id AND tournaments_id = :tournaments_id";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':sports_id'		=>	$_POST["sports_id"],
				':tournaments_id'	=>	$_SESSION["tournaments_id"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$sports_id = $row2['sports_id'];
		}
		if($sports_id == trim($_POST["sports_id"]))
		{
            echo "This sport is already exists in this tournament.";
		}
		else
		{
			$query = "
			INSERT INTO tournament_sports (sports_id, tournaments_id, date_created) 
			VALUES (:sports_id, :tournaments_id, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':sports_id'	    =>	trim($_POST["sports_id"]),
					':tournaments_id'	=>	$_SESSION['tournaments_id'],
					':date_created'		=>	date("Y-m-d")
				)
			);
			if(isset($result))
			{
				echo "Sport Added.";
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM tournament_sports WHERE tournament_sports_id = :tournament_sports_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':tournament_sports_id'	=>	$_POST["tournament_sports_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['tournament_sports_id'] = $row['tournament_sports_id'];
			$output['sports_id'] = $row['sports_id'];
		}
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE tournament_sports set sports_id = :sports_id
		WHERE tournament_sports_id = :tournament_sports_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':sports_id'		        =>	$_POST["sports_id"],
				':tournament_sports_id'		=>	$_POST["tournament_sports_id"]
			)
		);
		if(isset($result))
		{
			echo "Sport Edited.";
		}
	}

	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM tournament_sports
		WHERE tournament_sports_id = :tournament_sports_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':tournament_sports_id'		=>	$_POST["tournament_sports_id"]
			)
		);
		if(isset($result))
		{
			echo 'Sport Deleted.';
		}
	}

	if($_POST['btn_action'] == 'fetch_athletes')
	{
		$query = "SELECT tournament_sports.*, sports.* FROM tournament_sports 
		INNER JOIN sports ON tournament_sports.sports_id = sports.sports_id 
		where tournament_sports.tournament_sports_id = :tournament_sports_id ";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':tournament_sports_id'		=>	$_POST["sports_id"],
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{	
			$_SESSION['sports_id'] = $row["sports_id"];
			$_SESSION['sports_name'] =  $row['sports_name'].' - '.$row['category'];
		}
	}

}

?>