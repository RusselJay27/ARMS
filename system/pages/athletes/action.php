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
		$athletes_status = '';
		$query2 = "SELECT * FROM athletes WHERE athletes_last = :athletes_last and athletes_first = :athletes_first and athletes_mi = :athletes_mi";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':athletes_last'		=>	$_POST["athletes_last"],
				':athletes_first'	=>	$_POST["athletes_first"],
				':athletes_mi'		=>	$_POST["athletes_mi"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row)
		{
			$athletes_status = $row["athletes_status"];
			$fn = $row["athletes_last"].$row["athletes_first"].$row["athletes_mi"];
		}
	
		if(trim($_POST["athletes_last"]).trim($_POST["athletes_first"]).trim($_POST["athletes_mi"]) == $fn)
		{
			if($athletes_status == 'Active')
			{
				echo "This athlete is already exists in the database.";
			}
			else
			{
				echo "This athlete is already exists in the database but the status is Inactive.";
			}
		}
		else
		{
			$query = "
			INSERT INTO athletes (athletes_last,athletes_first,athletes_mi,gender,birthdate,height,weight,contact,email,
			address,coaches_id,level_id,school_id,scholar,varsity,class_a, image, date_created) 
			VALUES (:athletes_last, :athletes_first, :athletes_mi,:gender, :birthdate, :height,:weight, :contact, :email, 
			:address,:coaches_id, :level_id, :school_id,:scholar, :varsity, :class_a, :image, :date_created)
			";	
			$statement = $connect->prepare($query);
			$result = $statement->execute(
			array(
				':athletes_last'	=>	trim($_POST["athletes_last"]),
				':athletes_first'	=>	trim($_POST["athletes_first"]),
				':athletes_mi'		=>	trim($_POST["athletes_mi"]),
				':gender'			=>	trim($_POST["gender"]),
				':birthdate'		=>	trim($_POST["birthdate"]),
				':height'			=>	trim($_POST["height"]),
				':weight'			=>	trim($_POST["weight"]),
				':contact'			=>	trim($_POST["contact"]),
				':email'			=>	trim($_POST["email"]),
				':address'			=>	trim($_POST["address"]),
				':coaches_id'		=>	trim($_POST["coaches_id"]),
				':level_id'			=>	trim($_POST["level_id"]),
				':school_id'		=>	trim($_POST["school_id"]),
				':scholar'			=>	trim($_POST["scholar"]),
				':varsity'			=>	trim($_POST["varsity"]),
				':class_a'			=>	trim($_POST["class_a"]),
				':image'			=>	$path,
				':date_created'		=>	date("m-d-Y")
				)
			);
			if(isset($result))
			{
				echo 'Athlete Added.';
			}
		}
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "SELECT * FROM athletes WHERE athletes_id = :athletes_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':athletes_id'	=>	$_POST["athletes_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['level_id'] = $row['level_id'];
			$output['school_id'] = $row['school_id'];
			$output['scholar'] = $row['scholar'];
			$output['varsity'] = $row['varsity'];
			$output['class_a'] = $row['class_a'];
			$output['athletes_last'] = $row['athletes_last'];
			$output['athletes_first'] = $row['athletes_first'];
			$output['athletes_mi'] = $row['athletes_mi'];
			$output['coaches_id'] = $row['coaches_id'];
			$output['birthdate'] = $row['birthdate'];
			$output['address'] = $row['address'];
			$output['gender'] = $row['gender'];
			$output['contact'] = $row['contact'];
			$output['email'] = $row['email'];
			$output['height'] = $row['height'];
			$output['weight'] = $row['weight'];
			$output['image'] = $row['image'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		if(empty($_FILES['file']['tmp_name']))
		{
			$query = "
			UPDATE athletes SET 
				athletes_last = '".trim($_POST["athletes_last"])."',
				athletes_first = '".trim($_POST["athletes_first"])."',
				athletes_mi = '".trim($_POST["athletes_mi"])."',
				gender = '".trim($_POST["gender"])."',
				birthdate = '".trim($_POST["birthdate"])."',
				height = '".trim($_POST["height"])."',
				weight = '".trim($_POST["weight"])."',
				contact = '".trim($_POST["contact"])."',
				email = '".trim($_POST["email"])."',
				address = '".trim($_POST["address"])."',
				coaches_id = '".trim($_POST["coaches_id"])."',
				level_id = '".trim($_POST["level_id"])."',
				school_id = '".trim($_POST["school_id"])."',
				scholar = '".trim($_POST["scholar"])."',
				varsity = '".trim($_POST["varsity"])."',
				class_a = '".trim($_POST["class_a"])."'
				WHERE athletes_id = '".$_POST["athletes_id"]."'
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute();
			if(isset($result))
			{
				echo "Athlete Edited.";
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
					UPDATE athletes SET 
						athletes_last = '".trim($_POST["athletes_last"])."',
						athletes_first = '".trim($_POST["athletes_first"])."',
						athletes_mi = '".trim($_POST["athletes_mi"])."',
						gender = '".trim($_POST["gender"])."',
						birthdate = '".trim($_POST["birthdate"])."',
						height = '".trim($_POST["height"])."',
						weight = '".trim($_POST["weight"])."',
						contact = '".trim($_POST["contact"])."',
						email = '".trim($_POST["email"])."',
						address = '".trim($_POST["address"])."',
						coaches_id = '".trim($_POST["coaches_id"])."',
						level_id = '".trim($_POST["level_id"])."',
						school_id = '".trim($_POST["school_id"])."',
						scholar = '".trim($_POST["scholar"])."',
						varsity = '".trim($_POST["varsity"])."',
						class_a = '".trim($_POST["class_a"])."',
						image = '".$path."'
						WHERE athletes_id = '".$_POST["athletes_id"]."'
					";
					$statement = $connect->prepare($query);
					$result = $statement->execute();
					if(isset($result))
					{
						echo "Athlete Edited.";
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
		DELETE FROM athletes
		WHERE athletes_id = :athletes_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':athletes_id'		=>	$_POST["athletes_id"]
			)
		);
		if(isset($result))
		{
			echo 'Athlete Deleted.';
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
		UPDATE athletes 
		SET athletes_status = :athletes_status 
		WHERE athletes_id = :athletes_id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':athletes_status'	=>	$status,
				':athletes_id'		=>	$_POST["athletes_id"]
			)
		);
		if(isset($result))
		{
			echo "Athlete Status change to " . $status .".";
		}
	}

	if($_POST['btn_action'] == 'athletes_details')
	{
		$query = "SELECT athletes.*, sports.sports_name, grade_level.level_name, schools.school_name, coaches.coaches_last, coaches.coaches_first, coaches.coaches_mi
		FROM athletes 
		INNER JOIN coaches ON athletes.coaches_id = coaches.coaches_id 
		INNER JOIN sports ON coaches.sports_id = sports.sports_id 
		INNER JOIN grade_level ON athletes.level_id = grade_level.level_id 
		INNER JOIN schools ON athletes.school_id = schools.school_id 
		WHERE athletes_id = '".$_POST["athletes_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '
      	<div class="table-responsive">  
           <table class="table table-bordered">';
		foreach($result as $row)
		{
			//date in mm/dd/yyyy format; or it can be in other formats as well
			$birthDate = $row['birthdate'];
			//explode the date to get month, day and year
			$birthDate = explode("/", $birthDate);
			//get age from date or birthdate
			$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
			? ((date("Y") - $birthDate[2]) - 1)
			: (date("Y") - $birthDate[2])); //$age
			$output .= '
			<tr>
				<td colspan="2">
				<div class="col-6"  style="float:none;margin:auto;">
				<img src="'.$row['image'].'" alt="Default Avatar" class="img-thumbnail" >
				</div>
				</td>
			</tr>
			<tr>
				<td>Fullname</td>
				<td>'.$row['athletes_last'].', '.$row['athletes_first'].' '.$row['athletes_mi'].'.'.'</td>
			</tr>
			<tr>
				<td>Gender</td>
				<td>'.$row['gender'].'</td>
			</tr>
			<tr>
				<td>Age</td>
				<td>'.$age.'</td>
			</tr>
			<tr>
				<td>Birthdate</td>
				<td>'.$row['birthdate'].'</td>
			</tr>
			<tr>
				<td>Height</td>
				<td>'.$row['height'].'</td>
			</tr>
			<tr>
				<td>Weight</td>
				<td>'.$row['weight'].'</td>
			</tr>
			<tr>
				<td>Contact</td>
				<td>'.$row['contact'].'</td>
			</tr>
			<tr>
				<td>Email</td>
				<td>'.$row['email'].'</td>
			</tr>
			<tr>
				<td>Address</td>
				<td>'.$row['address'].'</td>
			</tr>
			<tr>
				<td>Sport</td>
				<td>'.$row['sports_name'].'</td>
			</tr>
			<tr>
				<td>Coach</td>
				<td>'.$row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.'.'</td>
			</tr>
			<tr>
				<td>Grade Level</td>
				<td>'.$row['level_name'].'</td>
			</tr>
			<tr>
				<td>School</td>
				<td>'.$row['school_name'].'</td>
			</tr>
			<tr>
				<td>MSP Scholar</td>
				<td>'.$row['scholar'].'</td>
			</tr>
			<tr>
				<td>School Varsity </td>
				<td>'.$row['varsity'].'</td>
			</tr>
			<tr>
				<td>Class-A Athlete </td>
				<td>'.$row['class_a'].'</td>
			</tr>
			<tr>
				<td>Date Created</td>
				<td>'.$row['date_created'].'</td>
			</tr>
			';
		}
		$output .= '
			</table>
		</div>
		';
		echo $output;
	}
}

?>