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
$dbPassword = 'password';

//session_start();

//pulls userName from textbox
//$userName = $_SESSION["userName"];
$userName = 'Bharat';

//$newAddress = $_POST["address"];
$newAddress = "kuchbhi";

//$newBlock = $_POST["block"];
$newBlock = 'b2';

//$newHood = $_POST["hood"];
$newHood = 'h1';

//$newZipCode = intval($_GET["zipCode"]);
$newZipCode = 1101;

$log->info("Storing the session variable 'userName' ");

try {
	$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
	$log->info("Connection is established with the database.");
	$stmt= $dbh->prepare('select block,hood from registeredUser where userName= ? and approvalStatus="approved" ;');
	$stmt->execute(array($userName));
	$results = $stmt->fetchAll();
	
	foreach($results as $row){
		$block=$row['block'];
		$hood=$row['hood'];
	}
	
	
	$stmt=$stmt = $dbh->prepare("CALL insert_into_localityhistory(?,?,?)");
	
	if($stmt->execute(array($userName,$block,$hood))){
		$stmt = $dbh->prepare("UPDATE registeredUser SET hood=? , block=?,address=?, zipCode=?, approvalStatus='pending', approvalTimestamp=NULL WHERE username=?;");
		if ($stmt->execute(array($newHood,$newBlock,$newAddress,$newZipCode,$userName))==1) {
  		
			$stmt = $dbh->prepare("CALL insert_into_approvals_after_LocationChange(?,?,?)");
			if ($stmt->execute(array($userName,$newBlock,$newHood))) {
				$stmt = $dbh->prepare("CALL update_receipients_status(?)");
				if($stmt->execute(array($userName))){
  					echo "Success";
				}
 			}
		//echo "Success";
 		}
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
