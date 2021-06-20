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
		if(empty($_FILES['file']['tmp_name']))
		{
			$path = '../../assets/img/default-placeholder.jpg';
		}
		else
		{
			$data = explode(".", $_FILES["file"]["name"]);
			$extension = $data[1];
			$allowed_extension = array("jpg", "png");
			if(in_array($extension, $allowed_extension))
			{
				$new_file_name = rand() . '.' . $extension;
				$path = '../../assets/img/' . $new_file_name;
				if(!move_uploaded_file($_FILES["file"]["tmp_name"], $path))
				{
					echo 'There is some error in uploading your image.';
				}
			}
			else
			{
				echo 'Invalid Image File.';
			}
		}
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
			INSERT INTO coaches (coaches_last, coaches_first, coaches_mi, birthdate, street, barangay, city, gender, contact, email,  password, image, date_created) 
			VALUES (:coaches_last, :coaches_first, :coaches_mi, :birthdate, :street, :barangay, :city, :gender, :contact, :email, :password, :image, :date_created)
			";	
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':coaches_last'		=>	ucfirst(trim($_POST["coaches_last"])),
					':coaches_first'	=>	ucfirst(trim($_POST["coaches_first"])),
					':coaches_mi'		=>	ucfirst(trim($_POST["coaches_mi"])),
					':birthdate'		=>	trim($_POST["birthdate"]),
					':street'	    	=>	ucfirst(trim($_POST["street"])),
					':barangay'	    	=>	ucfirst(trim($_POST["barangay"])),
					':city'	    		=>	ucfirst(trim($_POST["city"])),
					':gender'			=>	trim($_POST["gender"]),
					':contact'			=>	trim($_POST["contact"]),
					':email'			=>	trim($_POST["email"]),
					':password'			=>	password_hash(trim($_POST["password"]), PASSWORD_DEFAULT),
					':image'			=>	$path,
					':date_created'		=>	date("m-d-Y")
				)
			);
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
			$output['birthdate'] = $row['birthdate'];
			$output['street'] = $row['street'];
			$output['barangay'] = $row['barangay'];
			$output['city'] = $row['city'];
			$output['gender'] = $row['gender'];
			$output['contact'] = $row['contact'];
			$output['email'] = $row['email'];
			$output['image'] = $row['image'];
			//$output['password'] = $row['password'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		
		if(empty($_FILES['file']['tmp_name']))
		{
			$query = "
			UPDATE coaches SET 
				coaches_last = '".ucfirst(trim($_POST["coaches_last"]))."',
				coaches_first = '".ucfirst(trim($_POST["coaches_first"]))."',
				coaches_mi = '".ucfirst(trim($_POST["coaches_mi"]))."',
				birthdate = '".trim($_POST["birthdate"])."',
				street = '".ucfirst(trim($_POST["street"]))."',
				barangay = '".ucfirst(trim($_POST["barangay"]))."',
				city = '".ucfirst(trim($_POST["city"]))."',
				gender = '".trim($_POST["gender"])."',
				contact = '".trim($_POST["contact"])."',
				email = '".trim($_POST["email"])."'
				WHERE coaches_id = '".$_POST["coaches_id"]."'
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute();
			if(isset($result))
			{
				echo "Coach Edited.";
			}
		}
		else
		{
			$data = explode(".", $_FILES["file"]["name"]);
			$extension = $data[1];
			$allowed_extension = array("jpg", "png");
			if(in_array($extension, $allowed_extension))
			{
				$new_file_name = rand() . '.' . $extension;
				$path = '../../assets/img/' . $new_file_name;
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $path))
				{
					$query = "
					UPDATE coaches SET 
						coaches_last = '".ucfirst(trim($_POST["coaches_last"]))."',
						coaches_first = '".ucfirst(trim($_POST["coaches_first"]))."',
						coaches_mi = '".ucfirst(trim($_POST["coaches_mi"]))."',
						birthdate = '".trim($_POST["birthdate"])."',
						street = '".ucfirst(trim($_POST["street"]))."',
						barangay = '".ucfirst(trim($_POST["barangay"]))."',
						city = '".ucfirst(trim($_POST["city"]))."',
						gender = '".trim($_POST["gender"])."',
						contact = '".trim($_POST["contact"])."',
						email = '".trim($_POST["email"])."',
						image = '".$path."'
						WHERE coaches_id = '".$_POST["coaches_id"]."'
					";
					$statement = $connect->prepare($query);
					$result = $statement->execute();
					if(isset($result))
					{
						echo "Coach Edited.";
					}
				}
				else
				{
					echo 'There is some error in uploading your image.';
				}
			}
			else
			{
				echo 'Invalid Image File.';
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
		if(isset($result))
		{
			echo "Coach Status change to " . $status .".";
		}
	}
	
	if($_POST['btn_action'] == 'fetch_sports')
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
			$_SESSION['coaches_id'] = $_POST["coaches_id"];
			$_SESSION['coaches_fullname'] =  $row["coaches_last"].', '.$row["coaches_first"].' '.$row["coaches_mi"].'.';
		}
	}
}

?>