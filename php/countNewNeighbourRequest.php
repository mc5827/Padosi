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
	//$userName = $_SESSION["userName"];
	$userName = 'Sanjitha';

	//session_start();
	//$_SESSION['username'] = $phoneNum;
	//$log->info("Storing the session variable 'phoneNum' ");

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

		$stmt= $dbh->prepare('select count(userName) from registeredUser where block=? and hood=? and approvalStatus="pending" ;');
		$stmt->execute(array($block,$hood));
		$results = $stmt->fetchAll();

		$counter=0;
		foreach ($results as $rows){
			$counter++;
		}

		echo $counter;



		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}

?>
