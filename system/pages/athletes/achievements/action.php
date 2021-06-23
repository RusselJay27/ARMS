<?php

//action.php

include('../../database_connection.php');
include('../../function.php');

if(isset($_POST['btn_action']))
{
	if($_POST["btn_action"] == 'change')
	{  
		$output = '';  
		if($_POST["tournaments_id"] != '')  
		{
			$output = '<div class="form-group">
							<select name="sports_id" id="sports_id" class="form-control" required>
							<option value="">Select Sport *</option>';
							
			$query = "SELECT tournament_athletes.*, sports.* from tournament_athletes 
			INNER JOIN sports ON tournament_athletes.sports_id = sports.sports_id
			where tournament_athletes.status = 'Active' AND tournament_athletes.athletes_id = '".$_SESSION['athletes_id']."' AND tournament_athletes.tournaments_id = :tournaments_id";
			$statement = $connect->prepare($query);
			$statement->execute(
				array(
					':tournaments_id'	=> $_POST["tournaments_id"]
				)
			);
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output .= '<option value="'.$row["sports_id"].'">'.$row["sports_name"].' - '.$row["category"].'</option>';
			}
			$output .= '</select> </div>';
		}
		else{$output = '<div class="form-group">
			<select name="sports_id" id="sports_id" class="form-control" required>
				<option value="">Select Sport *</option>
				</select> </div>';
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
		$query2 = "SELECT * FROM achievements WHERE tournaments_id = :tournaments_id AND sports_id = :sports_id AND athletes_id = :athletes_id";
		$statement2 = $connect->prepare($query2);
		$statement2->execute(
			array(
				':tournaments_id'	=>	$_POST["tournaments_id"],
				':sports_id'	=>	$_POST["sports_id"],
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
            echo "This tournament and sport are already exists in this tournament.";
		}
		else
		{
			$query = "
			INSERT INTO achievements (tournaments_id, sports_id, athletes_id, award, date_created) 
			VALUES (:tournaments_id, :sports_id, :athletes_id, :award, :date_created)
			";
			$statement = $connect->prepare($query);
			$result = $statement->execute(
				array(
					':tournaments_id'	=>	$_POST["tournaments_id"],
					':sports_id'	    =>	$_POST["sports_id"],
					':athletes_id'		=>	$_SESSION['athletes_id'],
					':award'	    	=>	$_POST["award"],
					':date_created'		=>	date("Y-m-d")
				)
			);
			if(isset($result))
			{
				echo "Award Added.";
			}
		}
	}

	if($_POST['btn_action'] == 'delete')
	{
		$query = "
		DELETE FROM achievements
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
			echo 'Award Deleted.';
		}
	}
}

?>