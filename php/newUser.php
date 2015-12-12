<?php
// Insert the path where you unpacked log4php
include('../apache-log4php-2.3.0/src/main/php/Logger.php');

// Tell log4php to use our configuration file.
Logger::configure('..\config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('Padosi');

$selected=array();
//$selected = $_POST["selected"]; //pulls value of radio button
//echo "Padosi";




//$dbServerName = 'localhost';
$dbUserName = 'root';
$dbPassword = '';

//pulls userName from textbox
$userName = $_POST["userName"];
//$userName = 'some78';

//pulls userPassword from textbox
$userPassword = $_POST["userPassword"];
//$userPassword = 'mohit12344';

$address = $_POST["address"];
//$address = "kuchbhi";

$block = $_POST["block"];
//$block = 'b1';

$hood = $_POST["hood"];
//$hood = 'h1';

$zipCode = intval($_POST["zipCode"]);
//$zipCode = 1101;

$location = $_POST["feedLocation"];

$userProfile = $_POST["userProfile"];
//$userProfile = 'userProfileeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee';

$photoType =$_POST["photoType"];
//$photoType ="file";
if($photoType=="file"){
	$photo = addslashes($_FILES['fileImage']['tmp_name']);
	$log->info("after addslaseshes:".$photo);
	$photo = file_get_contents($photo);
	$photo = base64_encode($photo);
}
else{
	$photo = file_get_contents('C:\\xampp\\htdocs\\Padosi\\images\\'.$userName.'.jpg');
	$photo = base64_encode($photo);
}

//echo $photo;
//session_start();
//$_SESSION['username'] = $userName;
$log->info("Storing the session variable 'userName' ");
$log->info($userName.' '.$userPassword.' '.$address.' '.$block.' '.$hood.' '.$zipCode.' '.$location.' '.$userProfile.' '.$photoType);
$log->info($photo);
try {
	$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
	$log->info("Connection is established with the database.");
	$stmt = $dbh->prepare("CALL insert_New_User(?,?,?,?,?,?,?,?,?)");

	$log->info("calling procedure insert_New_User()");
	if ($stmt->execute(array($userName,$userPassword,$address,$block,$hood,$zipCode,$location,$userProfile,$photo))) {
  		echo "Success";
 	}
 	else{
 		$log->error('Some problem calling insert_New_User() procedure');
 		echo 'Failure';
 	}


	$stmt = null; // doing this is mandatory for connection to get closed
	$dbh = null;


} catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	$log->error("Error!: " . $e->getMessage() . "<br/>");
}

?>
