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
		$fn = '';
		$coaches_status = '';
		$query2 = "SELECT * FROM coaches WHERE coaches_last = :coaches_last and coaches_first = :coaches_first and coaches_mi = :coaches_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':coaches_last'		=>	$_POST["coaches_last"],
				':coaches_first'	=>	$_POST["coaches_first"],
				':coaches_mi'		=>	$_POST["coaches_mi"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row)
		{
			$coaches_status = $row["coaches_status"];
			$fn = $row["coaches_last"].$row["coaches_first"].$row["coaches_mi"];
		}

		if(trim($_POST["coaches_last"]).trim($_POST["coaches_first"]).trim($_POST["coaches_mi"]) == $fn)
		{
			if($coaches_status == 'Active')
			{
				echo "This coach is already exists in the database.";
			}
			else
			{
				echo "This coach is already exists in the database but the status is Inactive.";
			}
		}
		else
		{
			$query = "
			INSERT INTO coaches (coaches_last,coaches_first,coaches_mi,sports_id,birthdate,address,gender,contact,email) 
			VALUES (:coaches_last, :coaches_first, :coaches_mi,:sports_id, :birthdate, :address,:gender, :contact, :email)
			";	
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':coaches_last'		=>	trim($_POST["coaches_last"]),
					':coaches_first'	=>	trim($_POST["coaches_first"]),
					':coaches_mi'		=>	trim($_POST["coaches_mi"]),
					':sports_id'		=>	trim($_POST["sports_id"]),
					':birthdate'		=>	trim($_POST["birthdate"]),
					':address'			=>	trim($_POST["address"]),
					':gender'			=>	trim($_POST["gender"]),
					':contact'			=>	trim($_POST["contact"]),
					':email'			=>	trim($_POST["email"])
				)
			);
			//$result = $statement->fetchAll();
			if(isset($result))
			{
				echo 'Coach Added.';
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM coaches WHERE coaches_id = :coaches_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':coaches_id'	=>	$_POST["coaches_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['coaches_last'] = $row['coaches_last'];
			$output['coaches_first'] = $row['coaches_first'];
			$output['coaches_mi'] = $row['coaches_mi'];
			$output['sports_id'] = $row['sports_id'];
			$output['birthdate'] = $row['birthdate'];
			$output['address'] = $row['address'];
			$output['gender'] = $row['gender'];
			$output['contact'] = $row['contact'];
			$output['email'] = $row['email'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$fn = '';
		$query2 = "SELECT * FROM coaches WHERE coaches_last = :coaches_last and coaches_first = :coaches_first and coaches_mi = :coaches_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':coaches_last'	=>	$_POST["coaches_last"],
				':coaches_first'	=>	$_POST["coaches_first"],
				':coaches_mi'		=>	$_POST["coaches_mi"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$fn = $row2["coaches_last"].$row2["coaches_first"].$row2["coaches_mi"];
		}
		if(trim($_POST["coaches_last"]).trim($_POST["coaches_first"]).trim($_POST["coaches_mi"]) == $fn)
		{
			echo "This coach is already exists in the database.";
		}
		else
		{
			$query = "
			UPDATE coaches SET 
				coaches_last = '".trim($_POST["coaches_last"])."',
				coaches_first = '".trim($_POST["coaches_first"])."',
				coaches_mi = '".trim($_POST["coaches_mi"])."',
				sports_id = '".trim($_POST["sports_id"])."',
				birthdate = '".trim($_POST["birthdate"])."',
				address = '".trim($_POST["address"])."',
				gender = '".trim($_POST["gender"])."',
				contact = '".trim($_POST["contact"])."',
				email = '".trim($_POST["email"])."'
				WHERE coaches_id = '".$_POST["coaches_id"]."'
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute();
			//$result = $statement->fetchAll();
			if(isset($result))
			{
				echo "Coach Edited.";
			}
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM coaches
		WHERE coaches_id = :coaches_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':coaches_id'		=>	$_POST["coaches_id"]
			)
		);
		//$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Coach Deleted.';
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
		UPDATE coaches 
		SET coaches_status = :coaches_status 
		WHERE coaches_id = :coaches_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':coaches_status'	=>	$status,
				':coaches_id'		=>	$_POST["coaches_id"]
			)
		);
		//$result = $statement->fetchAll();
		if(isset($result))
		{
			echo "Coach Status change to " . $status .".";
		}
	}
}

?>