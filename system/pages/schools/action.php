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
		$school_name = '';
		$school_status = 'Active';
		$query2 = "SELECT * FROM schools WHERE school_name = :school_name";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':school_name'	=>	trim($_POST["school_name"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$school_name = $row2['school_name'];
			$school_status = $row2['school_status'];
		}
		if($school_name == trim($_POST["school_name"]))
		{
			if($school_status == 'Inactive')
			{
				echo "This school is already exists in the database but the status is Inactive.";
			}
			else
			{
				echo "This school is already exists in the database.";
			}
		}
		else
		{
			$query = "
			INSERT INTO schools (school_name,details, address) 
			VALUES (:school_name, :details, :address)
			";
			$statement = $connect->prepare($query);
			$statement->execute(
				array(
					':school_name'	=>	trim($_POST["school_name"]),
					':details'		=>	trim($_POST["details"]),
					':address'	    =>	trim($_POST["address"])
				)
			);
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo "School Added.";
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM schools WHERE school_id = :school_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':school_id'	=>	$_POST["school_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['school_id'] = $row['school_id'];
			$output['school_name'] = $row['school_name'];
			$output['details'] = $row['details'];
			$output['address'] = $row['address'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$school_name = '';
		$school_status = 'Active';
		$query2 = "SELECT * FROM schools WHERE school_name = :school_name";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':school_name'	=>	trim($_POST["school_name"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$school_name = $row2['school_name'];
		}
		if($school_name == trim($_POST["school_name"]))
		{
			echo "This school is already exists in the database.";
		}
		else
		{
			$query = "
			UPDATE schools set school_name = :school_name, details = :details, address = :address
			WHERE school_id = :school_id
			";
			$statement = $connect->prepare($query);
			$statement->execute(
				array(
					':school_name'		=>	$_POST["school_name"],
					':details'			=>	trim($_POST["details"]),
					':address'			=>	trim($_POST["address"]),
					':school_id'		=>	$_POST["school_id"]
				)
			);
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo "School Edited.";
			}
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM schools
		WHERE school_id = :school_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':school_id'		=>	$_POST["school_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'School Deleted.';
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
		UPDATE schools 
		SET school_status = :school_status 
		WHERE school_id = :school_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':school_status'	=>	$status,
				':school_id'		=>	$_POST["school_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo "School Status change to " . $status . ".";
		}
	}
}

?>