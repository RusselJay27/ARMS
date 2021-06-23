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
		$user_email = '';
		$fn = '';
		$user_status = '';
		$query2 = "SELECT * FROM user_account WHERE user_email = :user_email or user_last = :user_last and user_first = :user_first and user_mi = :user_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':user_email'	=>	trim($_POST["user_email"]),
				':user_last'	=>	trim($_POST["user_last"]),
				':user_first'	=>	trim($_POST["user_first"]),
				':user_mi'		=>	trim($_POST["user_mi"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row)
		{
			$user_status = $row["user_status"];
			$user_email = $row["user_email"];
			$fn = $row["user_last"].$row["user_first"].$row["user_mi"];
		}

		if($user_email == trim($_POST["user_email"]))
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
			INSERT INTO user_account (user_email, user_password, user_last,user_first,user_mi,date_created) 
			VALUES (:user_email, :user_password, :user_last, :user_first, :user_mi, :date_created)
			";	
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':user_email'		=>	trim($_POST["user_email"]),
					':user_password'	=>	password_hash(trim($_POST["user_password"]), PASSWORD_DEFAULT),
					':user_last'		=>	ucfirst(trim($_POST["user_last"])),
					':user_first'		=>	ucfirst(trim($_POST["user_first"])),
					':user_mi'			=>	ucfirst(trim($_POST["user_mi"])),
					':date_created'		=>	date("Y-m-d")
				)
			);
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
			$output['user_email'] = $row['user_email'];
			$output['user_last'] = $row['user_last'];
			$output['user_first'] = $row['user_first'];
			$output['user_mi'] = $row['user_mi'];
			$output['user_type'] = $row['user_type'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$user_email = '';
		$fn = '';
		$query2 = "SELECT * FROM user_account WHERE user_email = :user_email or user_last = :user_last and user_first = :user_first and user_mi = :user_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':user_email'	=>	trim($_POST["user_email"]),
				':user_last'	=>	trim($_POST["user_last"]),
				':user_first'	=>	trim($_POST["user_first"]),
				':user_mi'		=>	trim($_POST["user_mi"])
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$user_email = $row2['user_email'];
			$fn = $row2["user_last"].$row2["user_first"].$row2["user_mi"];
		}
		if($user_email == trim($_POST["user_email"]))
		{
			echo "This user's username is already exists in the database.";
		}
		else if(trim($_POST["user_last"]).trim($_POST["user_first"]).trim($_POST["user_mi"]) == $fn)
		{
			echo "This user is already exists in the database.";
		}
		else
		{
			if($_POST['user_password'] != '')
			{
				$query = "
				UPDATE user_account SET 
					user_email = '".trim($_POST["user_email"])."', 
					user_last = '".ucfirst(trim($_POST["user_last"]))."',
					user_first = '".ucfirst(trim($_POST["user_first"]))."',
					user_mi = '".ucfirst(trim($_POST["user_mi"]))."',
					user_password = '".password_hash($_POST["user_password"], PASSWORD_DEFAULT)."' 
					WHERE user_id = '".$_POST["user_id"]."'
				";
			}
			else
			{
				$query = "
				UPDATE user_account SET 
					user_email = '".trim($_POST["user_email"])."', 
					user_last = '".ucfirst(trim($_POST["user_last"]))."',
					user_first = '".ucfirst(trim($_POST["user_first"]))."',
					user_mi = '".ucfirst(trim($_POST["user_mi"]))."'
					WHERE user_id = '".$_POST["user_id"]."'
				";
			}
			$statement = $connect->prepare($query);
			$result = $statement->execute();
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
		$result = $statement->execute(
			array(
				':user_id'		=>	$_POST["user_id"]
			)
		);
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
		$result = $statement->execute(
			array(
				':user_status'	=>	$status,
				':user_id'		=>	$_POST["user_id"]
			)
		);
		if(isset($result))
		{
			echo "User Status change to " . $status .".";
		}
	}
}

?>