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
	//$userName = $_SESSION["registeredUsername"];
	$userName = 'sonu';


	$requestingUserName = $_POST['requestingUserName'];
	//$approvingUserName = 'vcc';
	//session_start();
	//$_SESSION['username'] = $phoneNum;
	//$log->info("Storing the session variable 'phoneNum' ");

	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
		$log->info("Connection is established with the database.");

		$stmt= $dbh->prepare('select approvalsReceived, approvalsRequired from approvals where userName= ?;');
		$stmt->execute(array($requestingUserName));
		$results = $stmt->fetchAll();

		foreach($results as $row){
			$approvalsReceived=$row['approvalsReceived'];
			$approvalsRequired=$row['approvalsRequired'];
		}

		if($approvalsRequired-$approvalsReceived==1){
			$stmt= $dbh->prepare('update approvals set approvalsReceived=approvalsReceived+1 where userName=?;');
			if ($stmt->execute(array($requestingUserName))>=0) {
				$stmt= $dbh->prepare('delete from approvals where userName=?;');
				if ($stmt->execute(array($requestingUserName))>=0){
					echo 'Success';
				}
			}
		}
		else{
			$stmt= $dbh->prepare('update approvals set approvalsReceived=approvalsReceived+1 where userName=?;');
			if ($stmt->execute(array($requestingUserName))>=0) {
				echo "Success";
			}
		}




		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}

?>
