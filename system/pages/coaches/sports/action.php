<?php

//action.php

include('../../database_connection.php');

if(isset($_POST['btn_action']))
{
    
	if($_POST['btn_action'] == 'back')
	{
        $_SESSION['coaches_id'] ='';
        $_SESSION['coaches_fullname'] ='';
	}
	if($_POST['btn_action'] == 'Add')
	{
		$sports_id = '';
		$query2 = "SELECT * FROM coach_sports WHERE sports_id = :sports_id AND coaches_id = :coaches_id";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':sports_id'	=>	$_POST["sports_id"],
				':coaches_id'	=>	$_SESSION["coaches_id"]
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
			INSERT INTO coach_sports (sports_id, coaches_id, date_created) 
			VALUES (:sports_id, :coaches_id, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':sports_id'	    =>	trim($_POST["sports_id"]),
					':coaches_id'		=>	$_SESSION['coaches_id'],
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
		$query = "SELECT * FROM coach_sports WHERE id = :id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['id'] = $row['id'];
			$output['sports_id'] = $row['sports_id'];
		}
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE coach_sports set sports_id = :sports_id
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':sports_id'	=>	$_POST["sports_id"],
				':id'			=>	$_POST["id"]
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
		DELETE FROM coach_sports
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