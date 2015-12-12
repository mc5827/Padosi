<?php
	// Insert the path where you unpacked log4php
	include('..\apache-log4php-2.3.0\src\main\php\Logger.php');

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
	//$userName = $_GET["userName"];
	$userName = 'Sandesh';

	//session_start();
	//$_SESSION['username'] = $phoneNum;
	//$log->info("Storing the session variable 'phoneNum' ");

	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
		$log->info("Connection is established with the database.");
		$stmt= $dbh->prepare('select sender from friends where receiver= ? and friendshipStatus="pending" ;');
		$stmt->execute(array($userName));
		$results = $stmt->fetchAll();

		$newFriendRequest = array();
		$counter=0;
		foreach ($results as $rows){
			$newFriendRequest[$counter++] = $rows['sender'];
		}

		echo json_encode($newFriendRequest);



		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}

?>
