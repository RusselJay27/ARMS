<?php

function fill_sports_list($connect)
{
	$query = "
	SELECT * from sports where sports_status = 'Active' order by sports_name asc
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["sports_id"].'">'.$row["sports_name"].'</option>';
	}
	return $output;
}
function fill_level_list($connect)
{
	$query = "
	SELECT * from grade_level where level_status = 'Active' order by level_name asc
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["level_id"].'">'.$row["level_name"].'</option>';
	}
	return $output;
}
function fill_schools_list($connect)
{
	$query = "
	SELECT * from schools where school_status = 'Active'  order by school_name asc
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["school_id"].'">'.$row["school_name"].'</option>';
	}
	return $output;
}
function fill_coaches_list($connect)
{
	$query = "
	SELECT * from coaches where coaches_status = 'Active' order by coaches_last asc
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["coaches_id"].'">'.$row["coaches_last"].', '.$row["coaches_first"].' '.$row["coaches_mi"].'.'.'</option>';
	}
	return $output;
}
function count_athletes($connect)
{
	$query = "SELECT * from athletes";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
function count_users($connect)
{
	$query = "SELECT * from user_account ";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
function count_tournaments($connect)
{
	$query = "SELECT * from tournaments";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
function count_schools($connect)
{
	$query = "SELECT * from schools";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
?>