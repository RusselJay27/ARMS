<?php

//action.php

include('../database_connection.php');
//include('function.php');
$pos = '';
$com = '';

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$sports_name = '';
		$category = '';
		$sports_status = 'Active';
		$query2 = "SELECT * FROM sports WHERE sports_name = :sports_name AND category = :category";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':sports_name'	=>	trim($_POST["sports_name"]),
				':category'		=>	$_POST["category"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$sports_name = $row2['sports_name'];
			$sports_status = $row2['sports_status'];
			$category = $row2['category'];
		}
		if($sports_name == trim($_POST["sports_name"]) && $category == $_POST["category"])
		{
			if($sports_status == 'Inactive')
			{
				echo "This sport is already exists in the database but the status is Inactive.";
			}
			else
			{
				echo "This sport is already exists in the database.";
			}
		}
		else
		{
			$query = "
			INSERT INTO sports (category, sports_name, details, date_created) 
			VALUES (:category, :sports_name, :details, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':category'		=>	$_POST["category"],
					':sports_name'	=>	ucfirst(trim($_POST["sports_name"])),
					':details'		=>	ucfirst(trim($_POST["details"])),
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
		$query = "SELECT * FROM sports WHERE sports_id = :sports_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':sports_id'	=>	$_POST["sports_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['sports_id'] = $row['sports_id'];
			$output['category'] = $row['category'];
			$output['sports_name'] = $row['sports_name'];
			$output['details'] = $row['details'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE sports set category = :category, sports_name = :sports_name, details = :details
		WHERE sports_id = :sports_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':category'		=>	$_POST["hidden_category"],
				':sports_name'	=>	ucfirst(trim($_POST["sports_name"])),
				':details'		=>	ucfirst(trim($_POST["details"])),
				':sports_id'	=>	$_POST["sports_id"]
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
		DELETE FROM sports
		WHERE sports_id = :sports_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':sports_id'		=>	$_POST["sports_id"]
			)
		);
		if(isset($result))
		{
			echo 'Sport Deleted.';
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
		UPDATE sports 
		SET sports_status = :sports_status 
		WHERE sports_id = :sports_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':sports_status'	=>	$status,
				':sports_id'		=>	$_POST["sports_id"]
			)
		);
		if(isset($result))
		{
			echo "Sport Status change to " . $status .".";
		}
	}
}

?>