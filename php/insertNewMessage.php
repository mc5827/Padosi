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

	//pulls userName from session
	//$userName = $_SESSION['username'];
	$userName = 'Sanjitha';

	$feedId = intval($_POST['feedId']);
	//$feedId =1;

	$messageText = $_POST['messageText'];
	//$messageText ='text';

	$location = $_POST['location'];
	//$location ='location';

	

	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
		$log->info("Connection is established with the database.");
		$stmt = $dbh->prepare('select count(*) as count from messages where feedId=?;');
		$stmt->execute(array($feedId));
		$result = $stmt->fetchAll();

		foreach($result as $row){
			$nextMessageId = $row['count']+1;
		}
		$log->info('next message id:'.$nextMessageId);

		$stmt = $dbh->prepare('insert into messages values (?,?,?,?,?,now())');
		if($stmt->execute(array($nextMessageId,$feedId,$userName,$messageText,$location))){
			$log->info('message inserted');
		}

		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}

?>
