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
	$dbPassword = 'password';
	
	//pulls userName from textbox
	$userName = $_POST["registeredUsername"];
	//$userName = 'sonu';
	
	//pulls userName from textbox
	$userPassword = $_POST["regiseteredUserPassword"];
	//$userPassword = 'mohit1';
	
	//session_start();
	//$_SESSION['username'] = $phoneNum;
	//$log->info("Storing the session variable 'phoneNum' ");
	
	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
		$log->info("Connection is established with the database.");
		$stmt= $dbh->prepare('select * from registeredUser where userName= ? and userPassword=? ;');
		$stmt->execute(array($userName,$userPassword));
		$userExist = $stmt->rowCount()==1;
	
		// call the stored procedure
		if($userExist) {
			echo 'Success';	
		}
		else {
			$log->info("username password not found");
			echo "Failure";
		}
	
	
	
		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}

?>	