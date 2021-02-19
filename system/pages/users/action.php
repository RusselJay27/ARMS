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
		$un = '';
		$fn = '';
		$user_status = '';
		$query2 = "SELECT * FROM user_account WHERE user_name = :user_name or user_last = :user_last and user_first = :user_first and user_mi = :user_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':user_name'	=>	$_POST["user_name"],
				':user_last'	=>	$_POST["user_last"],
				':user_first'	=>	$_POST["user_first"],
				':user_mi'		=>	$_POST["user_mi"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row)
		{
			$user_status = $row["user_status"];
			$user_name = $row["user_name"];
			$fn = $row["user_last"].$row["user_first"].$row["user_mi"];
		}

		if($user_name == trim($_POST["user_name"]))
		{
			if($user_status == 'Active')
			{
				echo "This user's username is already exists in the database.";
			}
			else
			{
				echo "This user's username is already exists in the database but the status is Inactive.";
			}
		}
		else if(trim($_POST["user_last"]).trim($_POST["user_first"]).trim($_POST["user_mi"]) == $fn)
		{
			if($user_status == 'Active')
			{
				echo "This user is already exists in the database.";
			}
			else
			{
				echo "This user is already exists in the database but the status is Inactive.";
			}
		}
		else
		{
			$query = "
			INSERT INTO user_account (user_name, user_password, user_last,user_first,user_mi) 
			VALUES (:user_name, :user_password, :user_last, :user_first, :user_mi)
			";	
			$statement = $connect->prepare($query);
			$statement->execute(
				array(
					':user_name'		=>	trim($_POST["user_name"]),
					':user_password'	=>	password_hash(trim($_POST["user_password"]), PASSWORD_DEFAULT),
					':user_last'		=>	trim($_POST["user_last"]),
					':user_first'		=>	trim($_POST["user_first"]),
					':user_mi'			=>	trim($_POST["user_mi"])
				)
			);
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo 'User Added.';
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM user_account WHERE user_id = :user_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_id'	=>	$_POST["user_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['user_name'] = $row['user_name'];
			$output['user_last'] = $row['user_last'];
			$output['user_first'] = $row['user_first'];
			$output['user_mi'] = $row['user_mi'];
			$output['user_type'] = $row['user_type'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$user_name = '';
		$fn = '';
		$query2 = "SELECT * FROM user_account WHERE user_name = :user_name or user_last = :user_last and user_first = :user_first and user_mi = :user_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':user_name'	=>	$_POST["user_name"],
				':user_last'	=>	$_POST["user_last"],
				':user_first'	=>	$_POST["user_first"],
				':user_mi'		=>	$_POST["user_mi"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$user_name = $row2['user_name'];
			$fn = $row2["user_last"].$row2["user_first"].$row2["user_mi"];
		}
		if($user_name == trim($_POST["user_name"]))
		{
			echo "This user's username is already exists in the database.";
		}
		else if(trim($_POST["user_last"]).trim($_POST["user_first"]).trim($_POST["user_mi"]) == $fn)
		{
			echo "This user is already exists in the database.";
		}
		else
		{
			if($_POST['user_account'] != '')
			{
				$query = "
				UPDATE user_details SET 
					user_name = '".trim($_POST["user_name"])."', 
					user_last = '".trim($_POST["user_last"])."',
					user_first = '".trim($_POST["user_first"])."',
					user_mi = '".trim($_POST["user_mi"])."',
					user_password = '".password_hash($_POST["user_password"], PASSWORD_DEFAULT)."' 
					WHERE user_id = '".$_POST["user_id"]."'
				";
			}
			else
			{
				$query = "
				UPDATE user_account SET 
					user_name = '".trim($_POST["user_name"])."', 
					user_last = '".trim($_POST["user_last"])."',
					user_first = '".trim($_POST["user_first"])."',
					user_mi = '".trim($_POST["user_mi"])."'
					WHERE user_id = '".$_POST["user_id"]."'
				";
			}
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo "User Edited.";
			}
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM user_account
		WHERE user_id = :user_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_id'		=>	$_POST["user_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'User Deleted.';
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
		UPDATE user_account 
		SET user_status = :user_status 
		WHERE user_id = :user_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_status'	=>	$status,
				':user_id'		=>	$_POST["user_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo "User Status change to " . $status .".";
		}
	}
}

?>