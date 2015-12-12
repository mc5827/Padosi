<?php
// Insert the path where you unpacked log4php
include('..\apache-log4php-2.3.0\src\main\php\Logger.php');

// Tell log4php to use our configuration file.
Logger::configure('..\config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('Padosi');

//$dbServerName = 'localhost';
$dbUserName = 'root';
$dbPassword = '';

//association will have either 'Friends(F sould be capital)' or 'neighbours' as value
$association = $_GET["choice"];
echo $association;
$log->info($association);

//pulls userName from session
//$userName = $_SESSION["userName"];
$userName = 'Sanjitha';


//session_start();
//$_SESSION['username'] = $userName;
$log->info("Storing the session variable 'userName' ");

try {
	$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
	$log->info("Connection is established with the database.");

	if('Friends'==$association)	{
		$stmt = $dbh->prepare("CALL friendList(?)");

		$log->info("calling procedure friendList(".$userName.")");

		if ($stmt->execute(array($userName))) {
			$results = $stmt->fetchAll();
			$personName = array();
			foreach ($results as $row ) {
				$temp = array();
				array_push($temp, $row['friendName'],$row['userProfile']);
				if($row['friendshipStatus']==null){
					array_push($temp, 'not friend');
				}
				else{
					array_push($temp,$row['friendshipStatus']);
				}
				array_push($personName, $temp);
			}

			echo json_encode($personName);
		}
		else{
				$log->error('Some problem calling friendList() procedure');
			echo 'Failure';
		}
	}
	else{
		$stmt = $dbh->prepare("CALL neighbourList(?)");
		$log->info("calling procedure neighbourList(".$userName.")");

		if ($stmt->execute(array($userName))) {
			$results = $stmt->fetchAll();
			$personName = array();
			foreach ($results as $row ) {
				$temp = array();
				array_push($temp, $row['neighbourName'],$row['userProfile']);
				if($row['neighbourStatus']==null){
					array_push($temp, 0);
				}
				else{
					array_push($temp,1);
				}
				array_push($personName, $temp);
			}

			echo json_encode($personName);
		}
		else{
			$log->error('Some problem calling neighbourList() procedure');
			echo 'Failure';
		}
	}




	$stmt = null; // doing this is mandatory for connection to get closed
	$dbh = null;
} catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	$log->error("Error!: " . $e->getMessage() . "<br/>");
}

?>
