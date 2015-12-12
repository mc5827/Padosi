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
	$userName = 'Bharat';

	$receiverName = $_POST['receiver'];
	//$receiverName = 'Sanjitha';

	$feedSubject = $_POST['feedSubject'];
	//$feedSubject ='subject';

	$feedText = $_POST['feedText'];
	//$feedText ='text';

	$location = $_POST['location'];
	//$location ='location';

	$category = $_POST['category'];
	//$category ='friend';

	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $dbUserName, $dbPassword);
		$log->info("Connection is established with the database.");
		$stmt = $dbh->prepare('select count(*) as count from feed;');
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach($result as $row){
			$nextFeedId = $row['count']+1;
		}
		$log->info('next feed id:'.$nextFeedId);

		$stmt = $dbh->prepare('insert into feed values (?,?,?,?,now(),?,?)');
		if($stmt->execute(array($nextFeedId,$feedSubject,$userName,$feedText,$location,$category))){


 			if($category=='friend' || $category=='neighbour'){
 				$stmt = $dbh->prepare('insert into receipient values (?,?,"approved")');
 				if($stmt->execute(array($nextFeedId,$userName))){

 					$log->info('sender added to receipient');
 					if($stmt = $dbh->prepare('insert into receipient values (?,?,"approved")')){
 						$stmt->execute(array($nextFeedId,$receiverName));
 						$log->info('receiver added to receipient');
 					}
 				}
 			}

		}
		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}

?>
