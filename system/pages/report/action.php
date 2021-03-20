<?php

//action.php

include('../database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST["btn_action"] == 'tournament_change')
	{  
		$output = '';  
		if($_POST["tournaments_id"] != '')  
		{
            $output = '
                <table id="report" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Athlete</th>
                            <th>Sport</th>
                            <th>Coach</th>
                            <th>Award</th>
                            <th>Date Event</th>
                        </tr>
                    </thead>
                    <tbody>';
                    		
			$query = " SELECT
                tournament_athletes.*, 
                athletes.athletes_last, 
                athletes.athletes_first, 
                athletes.athletes_mi, 
                sports.category, 
                sports.sports_name, 
                coaches.coaches_last, 
                coaches.coaches_first, 
                coaches.coaches_mi,
                (SELECT award from achievements WHERE athletes_id = tournament_athletes.athletes_id AND tournaments_id = tournament_athletes.tournaments_id AND sports_id = tournament_athletes.sports_id ) as award
            FROM
                tournament_athletes
            INNER JOIN athletes ON tournament_athletes.athletes_id = athletes.athletes_id
            INNER JOIN sports ON tournament_athletes.sports_id = sports.sports_id
            INNER JOIN coach_sports ON sports.sports_id = coach_sports.sports_id
            INNER JOIN coaches ON coach_sports.coaches_id = coaches.coaches_id
            WHERE
                tournament_athletes.tournaments_id = :tournaments_id
            ORDER BY award DESC ";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                    ':tournaments_id'	=> $_POST["tournaments_id"]
                )
            );
            $result = $statement->fetchAll();
            foreach($result as $row)
            {
				$award = '';
				if ($row['award'] == '3'){
					$award = 'Gold';
				}
				if ($row['award'] == '2'){
					$award = 'Silver';
				}
				if ($row['award'] == '1'){
					$award = 'Bronze';
				}
				$output .= '
                <tr>
                  <td >'.$row['athletes_last'].', '.$row['athletes_first'].' '.$row['athletes_mi'].'.'.'</td>
                  <td >'.$row["sports_name"].' - '.$row["category"].'</td>
                  <td >'.$row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.'.'</td>
                  <td >'.$award.'</td>
                  <td >'.$row['date_created'].'</td>
                </tr>
            ';
            }

            $output .= '</tbody>
                </table>';
		}
		else{
            $output = '
                <table id="report" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Athlete</th>
                            <th>Sport</th>
                            <th>Coach</th>
                            <th>Award</th>
                            <th>Date Event</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" style="text-align: center">Please select tournament above.</td>
                        </tr>
                    </tbody>
                </table>';
		}
		echo $output;
	}
}

?>