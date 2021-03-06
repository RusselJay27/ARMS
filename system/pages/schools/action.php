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
			INSERT INTO schools (school_name,details, street, barangay, city, date_created) 
			VALUES (:school_name, :details, :street, :barangay, :city, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':school_name'	=>	ucfirst(trim($_POST["school_name"])),
					':details'		=>	ucfirst(trim($_POST["details"])),
					':street'	    =>	ucfirst(trim($_POST["street"])),
					':barangay'	    =>	ucfirst(trim($_POST["barangay"])),
					':city'	    	=>	ucfirst(trim($_POST["city"])),
					':date_created'	=>	date("Y-m-d")
				)
			);
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
			$output['street'] = $row['street'];
			$output['barangay'] = $row['barangay'];
			$output['city'] = $row['city'];
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
			UPDATE schools set school_name = :school_name, details = :details, street = :street, barangay = :barangay, city = :city
			WHERE school_id = :school_id
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':school_name'	=>	ucfirst(trim($_POST["school_name"])),
					':details'		=>	ucfirst(trim($_POST["details"])),
					':street'	    =>	ucfirst(trim($_POST["street"])),
					':barangay'	    =>	ucfirst(trim($_POST["barangay"])),
					':city'	    	=>	ucfirst(trim($_POST["city"])),
					':school_id'		=>	$_POST["school_id"]
				)
			);
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
		$result = $statement->execute(
			array(
				':school_id'		=>	$_POST["school_id"]
			)
		);
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
		$result = $statement->execute(
			array(
				':school_status'	=>	$status,
				':school_id'		=>	$_POST["school_id"]
			)
		);
		if(isset($result))
		{
			echo "School Status change to " . $status . ".";
		}
	}
}

?>