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
		$level_name = '';
		$level_status = 'Active';
		$query2 = "SELECT * FROM grade_level WHERE level_name = :level_name";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':level_name'	=>	trim($_POST["level_name"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$level_name = $row2['level_name'];
			$level_status = $row2['level_status'];
		}
		if($level_name == trim($_POST["level_name"]))
		{
			if($level_status == 'Inactive')
			{
				echo "This grade level is already exists in the database but the status is Inactive.";
			}
			else
			{
				echo "This grade level is already exists in the database.";
			}
		}
		else
		{
			$query = "
			INSERT INTO grade_level (level_name) 
			VALUES (:level_name)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':level_name'	=>	trim($_POST["level_name"])
				)
			);
			//$result = $statement->fetchAll();
			if(isset($result))
			{
				echo "Grade Level Added.";
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM grade_level WHERE level_id = :level_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':level_id'	=>	$_POST["level_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['level_id'] = $row['level_id'];
			$output['level_name'] = $row['level_name'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$level_name = '';
		$level_status = 'Active';
		$query2 = "SELECT * FROM grade_level WHERE level_name = :level_name";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':level_name'	=>	trim($_POST["level_name"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$level_name = $row2['level_name'];
		}
		if($level_name == trim($_POST["level_name"]))
		{
			echo "This grade level is already exists in the database.";
		}
		else
		{
			$query = "
			UPDATE grade_level set level_name = :level_name
			WHERE level_id = :level_id
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':level_name'		=>	$_POST["level_name"],
					':level_id'		=>	$_POST["level_id"]
				)
			);
			//$result = $statement->fetchAll();
			if(isset($result))
			{
				echo "Grade Level Edited.";
			}
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM grade_level
		WHERE level_id = :level_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':level_id'		=>	$_POST["level_id"]
			)
		);
		//$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Grade Level Deleted.';
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
		UPDATE grade_level 
		SET level_status = :level_status 
		WHERE level_id = :level_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':level_status'	=>	$status,
				':level_id'		=>	$_POST["level_id"]
			)
		);
		//$result = $statement->fetchAll();
		if(isset($result))
		{
			echo "Grade Level Status change to " . $status . ".";
		}
	}
}

?>