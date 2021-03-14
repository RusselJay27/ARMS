<?php

//action.php

include('../database_connection.php');
//include('function.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$tournaments_name = '';
		$tournaments_status = 'Active';
		$query2 = "SELECT * FROM tournaments WHERE tournaments_name = :tournaments_name";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':tournaments_name'	=>	trim($_POST["tournaments_name"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$tournaments_name = $row2['tournaments_name'];
			$tournaments_status = $row2['tournaments_status'];
		}
		if($tournaments_name == trim($_POST["tournaments_name"]))
		{
			if($tournaments_status == 'Inactive')
			{
				echo "This tournament is already exists in the database but the status is Inactive.";
			}
			else
			{
				echo "This tournament is already exists in the database.";
			}
		}
		else
		{
			$query = "
			INSERT INTO tournaments (tournaments_name, details, type, date, date_created) 
			VALUES (:tournaments_name, :details, :type, :date, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':tournaments_name'	=>	trim($_POST["tournaments_name"]),
					':details'			=>	trim($_POST["details"]),
					':type'				=>	trim($_POST["type"]),
					':date'				=>	trim($_POST["date"]),
					':date_created'		=>	date("m-d-Y")
				)
			);
			if(isset($result))
			{
				echo "Tournament Added.";
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM tournaments WHERE tournaments_id = :tournaments_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':tournaments_id'	=>	$_POST["tournaments_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['tournaments_id'] = $row['tournaments_id'];
			$output['tournaments_name'] = $row['tournaments_name'];
			$output['details'] = $row['details'];
			$output['type'] = $row['type'];
			$output['date'] = $row['date'];
		}
		echo json_encode($output);
	}
	
	if($_POST['btn_action'] == 'fetch_sports')
	{
		$query = "SELECT * FROM tournaments WHERE tournaments_id = :tournaments_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':tournaments_id'	=>	$_POST["tournaments_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{	
			$_SESSION['tournaments_id'] = $_POST["tournaments_id"];
			$_SESSION['tournaments_name'] =  $row['tournaments_name'];
		}
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE tournaments set tournaments_name = :tournaments_name, details = :details, type = :type, date = :date
		WHERE tournaments_id = :tournaments_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':tournaments_name'		=>	$_POST["tournaments_name"],
				':details'				=>	$_POST["details"],
				':type'					=>	$_POST["type"],
				':date'					=>	$_POST["date"],
				':tournaments_id'		=>	$_POST["tournaments_id"]
			)
		);
		if(isset($result))
		{
			echo "Tournament Edited.";
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM tournaments
		WHERE tournaments_id = :tournaments_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':tournaments_id'		=>	$_POST["tournaments_id"]
			)
		);
		if(isset($result))
		{
			echo 'Tournament Deleted.';
		}
	}

	if($_POST['btn_action'] == 'status')
	{
		$status = 'Active';
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
		$query = "
		UPDATE tournaments 
		SET tournaments_status = :tournaments_status 
		WHERE tournaments_id = :tournaments_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':tournaments_status'	=>	$status,
				':tournaments_id'		=>	$_POST["tournaments_id"]
			)
		);
		if(isset($result))
		{
			echo "Tournament Status change to " . $status .".";
		}
	}
}

?>