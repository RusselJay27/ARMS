<?php

//action.php

include('../../../database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'back')
	{
        $_SESSION['sports_id'] ='';
        $_SESSION['sports_name'] ='';
	}
	
	if($_POST['btn_action'] == 'Add')
	{
		$sports_id = '';
		$query2 = "SELECT * FROM tournament_athletes WHERE sports_id = :sports_id AND tournaments_id = :tournaments_id AND athletes_id = :athletes_id";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':sports_id'		=>	$_SESSION["sports_id"],
				':tournaments_id'	=>	$_SESSION["tournaments_id"],
				':athletes_id'		=>	$_POST["athletes_id"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$sports_id = $row2['sports_id'];
		}
		if($sports_id == $_SESSION["sports_id"])
		{
            echo "This athlete is already exists in this tournament.";
		}
		else
		{
			$query = "
			INSERT INTO tournament_athletes (tournaments_id, sports_id, athletes_id, date_created) 
			VALUES (:tournaments_id, :sports_id, :athletes_id, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':sports_id'	=>	$_SESSION["sports_id"],
					':tournaments_id'	=>	$_SESSION["tournaments_id"],
					':athletes_id'	=>	$_POST["athletes_id"],
					':date_created'		=>	date("m-d-Y")
				)
			);
			if(isset($result))
			{
				echo "Sport Added.";
			}
		}
	}

	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM tournament_athletes
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':id'		=>	$_POST["id"]
			)
		);
		if(isset($result))
		{
			echo 'Sport Deleted.';
		}
	}
}

?>