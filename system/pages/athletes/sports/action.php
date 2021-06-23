<?php

//action.php

include('../../database_connection.php');
include('../../function.php');

if(isset($_POST['btn_action']))
{
	if($_POST["btn_action"] == 'change')
	{  
		$output = '';  
		if($_POST["sports_id"] != '')  
		{
			$output = '<div class="form-group">
							<select name="coaches_id" id="coaches_id" class="form-control" required>
							<option value="">Select Coach *</option>';
							
		$query = "SELECT coaches.* from coaches 
		INNER JOIN coach_sports ON coach_sports.coaches_id = coaches.coaches_id
		where coaches.coaches_status = 'Active' AND coach_sports.sports_id = :sports_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':sports_id'	=> $_POST["sports_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output .= '<option value="'.$row["coaches_id"].'">'.$row["coaches_last"].', '.$row["coaches_first"].' '.$row["coaches_mi"].'</option>';
		}

			$output .= '
			  				</select>
						</div>';
		}
		else{$output = '<div class="form-group">
				<select name="coaches_id" id="coaches_id" class="form-control" required>
					<option value="">Select Coach *</option>
				</select>
				</div>';
		}
		echo $output;
	}

	if($_POST['btn_action'] == 'back')
	{
        $_SESSION['athletes_id'] ='';
        $_SESSION['athletes_fullname'] ='';
	}

	if($_POST['btn_action'] == 'Add')
	{
		$sports_id = '';
		$query2 = "SELECT * FROM athlete_sports WHERE sports_id = :sports_id AND coaches_id = :coaches_id AND athletes_id = :athletes_id";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':sports_id'	=>	$_POST["sports_id"],
				':coaches_id'	=>	$_POST["coaches_id"],
				':athletes_id'	=>	$_SESSION["athletes_id"]
			)
		);
		$result2 = $statement2->fetchAll();
		foreach($result2 as $row2)
		{
			$sports_id = $row2['sports_id'];
		}
		if($sports_id == trim($_POST["sports_id"]))
		{
            echo "This sport is already exists in this tournament.";
		}
		else
		{
			$query = "
			INSERT INTO athlete_sports (sports_id, coaches_id, athletes_id, date_created) 
			VALUES (:sports_id, :coaches_id, :athletes_id, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':sports_id'	    =>	$_POST["sports_id"],
					':coaches_id'	    =>	$_POST["coaches_id"],
					':athletes_id'		=>	$_SESSION['athletes_id'],
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
		$query = "SELECT * FROM athlete_sports WHERE id = :id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['id'] = $row['id'];
			$output['sports_id'] = $row['sports_id'];
			$output['coaches_id'] = $row['coaches_id'];
		}
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE athlete_sports set sports_id = :sports_id, coaches_id = :coaches_id
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':sports_id'	=>	$_POST["sports_id"],
				':coaches_id'	=>	$_POST["coaches_id"],
				':id'			=>	$_POST["id"]
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
		DELETE FROM athlete_sports
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$result = $statement->execute(
			array(
				':id'		=>	$_POST["id"]
			)
		);
		if(isset($result))
		{
			echo 'Sport Deleted.';
		}
	}
}

?>