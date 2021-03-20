<?php

$connect = new PDO('mysql:host=localhost;dbname=record_mngmnt_yasdo', 'root', '');
session_start();

try{
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $tournaments = "CREATE table tournaments(
        `tournaments_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `tournaments_name` varchar(255) DEFAULT NULL,
        `details` varchar(255) DEFAULT NULL,
        `type` enum('International','National','Local') DEFAULT NULL,
        `date` varchar(255) DEFAULT NULL,
        `tournaments_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($tournaments);

    $sports = "CREATE table sports(
        `sports_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `category` varchar(255) DEFAULT NULL,
        `sports_name` varchar(255) DEFAULT NULL,
        `details` varchar(255) DEFAULT NULL,
        `sports_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($sports);

    $schools = "CREATE table schools(
        `school_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `school_name` varchar(255) DEFAULT NULL,
        `details` varchar(255) DEFAULT NULL,
        `address` varchar(255) DEFAULT NULL,
        `school_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($schools);

    $grade_level = "CREATE table grade_level(
        `level_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `level_name` varchar(255) DEFAULT NULL,
        `level_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($grade_level);

    $coaches = "CREATE table coaches(
        `coaches_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `coaches_last` varchar(255) DEFAULT NULL,
        `coaches_first` varchar(255) DEFAULT NULL,
        `coaches_mi` varchar(255) DEFAULT NULL,
        `birthdate` varchar(255) DEFAULT NULL,
        `address` varchar(255) DEFAULT NULL,
        `gender` enum('Male','Female') DEFAULT 'Male',
        `contact` varchar(255) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `image` varchar(255) DEFAULT NULL,
        `coaches_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($coaches);

    $athletes = "CREATE table athletes(
        `athletes_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `athletes_last` varchar(255) DEFAULT NULL,
        `athletes_first` varchar(255) DEFAULT NULL,
        `athletes_mi` varchar(255) DEFAULT NULL,
        `gender` enum('Male','Female') DEFAULT 'Male',
        `birthdate` varchar(255) DEFAULT NULL,
        `height` varchar(255) DEFAULT NULL,
        `weight` varchar(255) DEFAULT NULL,
        `contact` varchar(255) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `address` varchar(255) DEFAULT NULL,
        `level_id` INT(11) DEFAULT 0,
        `school_id` INT(11) DEFAULT 0,
        `scholar` enum('Yes','No') DEFAULT 'No',
        `varsity` enum('Yes','No') DEFAULT 'No',
        `class_a` enum('Yes','No') DEFAULT 'No',
        `image` varchar(255) DEFAULT NULL,
        `athletes_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($athletes);

    $tournament_sports = "CREATE table tournament_sports(
        `tournament_sports_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `tournaments_id` INT(11) DEFAULT 0,
        `sports_id` INT(11) DEFAULT 0,
        `tournament_sports_status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($tournament_sports);

    $coach_sports = "CREATE table coach_sports(
        `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `sports_id` INT(11) DEFAULT 0,
        `coaches_id` INT(11) DEFAULT 0,
        `status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($coach_sports);

    $athlete_sports = "CREATE table athlete_sports(
        `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `sports_id` INT(11) DEFAULT 0,
        `coaches_id` INT(11) DEFAULT 0,
        `athletes_id` INT(11) DEFAULT 0,
        `status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($athlete_sports);

    $tournament_athletes = "CREATE table tournament_athletes(
        `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `tournaments_id` INT(11) DEFAULT 0,
        `sports_id` INT(11) DEFAULT 0,
        `athletes_id` INT(11) DEFAULT 0,
        `status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($tournament_athletes);

    $achievements = "CREATE table achievements(
        `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `tournaments_id` INT(11) DEFAULT 0,
        `sports_id` INT(11) DEFAULT 0,
        `athletes_id` INT(11) DEFAULT 0,
        `award` INT(11) DEFAULT 0,
        `status` enum('Active','Inactive') DEFAULT 'Active',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($achievements);

    $date_today = date("m-d-Y");
    $insert_sports = "INSERT INTO `sports`
        (`sports_id`, `category`, `sports_name`, `details`, `sports_status`, `date_created`) 
        VALUES 
        (1,'Individual / Dual','Badminton','','Active','".$date_today."'),
        (2,'Individual / Dual','Chess','','Active','".$date_today."'),
        (3,'Individual / Dual','Table Tennis','','Active','".$date_today."'),
        (4,'Individual / Dual','Gymnastics','','Active','".$date_today."'),
        (5,'Individual / Dual','Athletics','','Active','".$date_today."'),
        (6,'Team','Volleyball','','Active','".$date_today."'),
        (7,'Team','Basketball','','Active','".$date_today."'),
        (8,'Team','Football / Futsal','','Active','".$date_today."'),
        (9,'Team','Baseball','','Active','".$date_today."'),
        (10,'Combative','Judo','','Active','".$date_today."'),
        (11,'Combative','Taekwondo','','Active','".$date_today."'),
        (12,'Combative','Arnis','','Active','".$date_today."'),
        (13,'Combative','Pencak Silat','','Active','".$date_today."')
    ;";
    $connect->exec($insert_sports);

    $recommended = "CREATE table recommended(
        `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `tournaments_id` INT(11) DEFAULT 0,
        `sports_id` INT(11) DEFAULT 0,
        `athletes_id` INT(11) DEFAULT 0,
        `status` enum('Accepted','Declined','Pending') DEFAULT 'Pending',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($recommended);

    $user_account = "CREATE table user_account(
        `user_id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `user_name` varchar(255) DEFAULT NULL,
        `user_password` varchar(255) DEFAULT NULL,
        `user_last` varchar(255) DEFAULT NULL,
        `user_first` varchar(255) DEFAULT NULL,
        `user_mi` varchar(255) DEFAULT NULL,
        `user_status` enum('Active','Inactive') DEFAULT 'Active',
        `user_type` enum('Admin','Staff') DEFAULT 'Staff',
        `date_created` varchar(255) DEFAULT null
    );";
    $connect->exec($user_account);

}
catch(PDOException $e){
    //echo $e->getMessage();
}
?>