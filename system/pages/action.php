<?php

//action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST["btn_action"] == 'tournament_change')
	{  
		$output = '';  
		if($_POST["tournaments_id"] != '')  
		{
			$output = '<div class="form-group">
							<select name="sports_id" id="sports_id" class="form-control" required>
							<option value="">Select Sport</option>';
							
			$query = "SELECT tournament_sports.*, sports.sports_name, sports.category FROM tournament_sports 
            INNER JOIN sports ON tournament_sports.sports_id = sports.sports_id 
            WHERE tournament_sports.tournaments_id = :tournaments_id";
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
		else{
            $output = '<div class="form-group">
			        <select name="sports_id" id="sports_id" class="form-control" required>
				<option value="">Select Sport</option>
				</select> </div>';
		}
		echo $output;
	}
	if($_POST["btn_action"] == 'ranking_change')
	{  
		$output = '';  
		if($_POST["ranking_tournament_id"] != '')  
		{
			$output = '
			<table id="ranking" class="table table-striped table-valign-middle">
			  <thead>
			  <tr>
				<th>Rank</th>
				<th>Athlete</th>
				<th>Award</th>
				<th>Sport</th>
				<th>Coach</th>
			  </tr>
			  </thead>
			  <tbody>';
			$count = 0;			
			$query = " SELECT
				max(achievements.award) as medalist,
				achievements.*,
				sports.sports_name,
				sports.category,
				athletes.athletes_last,
				athletes.athletes_first,
				athletes.athletes_mi,
				coaches.coaches_last,
				coaches.coaches_first,
				coaches.coaches_mi
			FROM
				achievements
				INNER JOIN sports ON achievements.sports_id = sports.sports_id
				INNER JOIN athletes ON achievements.athletes_id = athletes.athletes_id 
				INNER JOIN athlete_sports ON athletes.athletes_id = athlete_sports.athletes_id 
				AND sports.sports_id = athlete_sports.sports_id
				INNER JOIN coaches ON athlete_sports.coaches_id = coaches.coaches_id 
			WHERE
				achievements.tournaments_id = :tournaments_id
				GROUP BY achievements.athletes_id DESC
			ORDER BY
				achievements.award DESC  ";
			$statement = $connect->prepare($query);
			$statement->execute(
				array(
					':tournaments_id'	=> $_POST["ranking_tournament_id"]
				)
			);
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$count = $count + 1;
				$award = '';
				if ($row['medalist'] == '3'){
					$award = 'Gold Medalist';
				}
				if ($row['medalist'] == '2'){
					$award = 'Silver Medalist';
				}
				if ($row['medalist'] == '1'){
					$award = 'Bronze Medalist';
				}

				$output .= '
					  <tr>
						<td >'.$count.'</td>
						<td >'.$row['athletes_last'].', '.$row['athletes_first'].' '.$row['athletes_mi'].'.'.'</td>
						<td >'.$award.'</td>
						<td >'.$row["sports_name"].' - '.$row["category"].'</td>
						<td >'.$row['coaches_last'].', '.$row['coaches_first'].' '.$row['coaches_mi'].'.'.'</td>
					  </tr>
				';
			}
			$output .= '
			</tbody>
		  </table>';
		}
		else{
            $output = '
              <table id="ranking" class="table table-striped table-valign-middle">
                <thead>
                <tr>
                    <th>Rank</th>
                    <th>Athlete</th>
                    <th>Award</th>
                    <th>Sport</th>
                    <th>Coach</th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="5" style="text-align: center">No data found.</td>
                  </tr>
                </tbody>
              </table>
            ';
		}
		echo $output;
	}
}

?>