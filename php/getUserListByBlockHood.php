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
	$servername = 'localhost';
	$username = 'root';
	$password = '';

	//pulls userName from textbox
	//$userName = $_GET["userName"];
	$user = 'Sandesh';

	//$block = $_GET["block"];
	$block = 'bayridge';
	//$userName = $_GET["hood"];
	$hood = 'brooklyn';
	//$type = $_GET["type"];
	$type = 'Hood';
	$columns = array('userName','block','hood','address','location');
	//session_start();
	//$_SESSION['username'] = $phoneNum;
	//$log->info("Storing the session variable 'phoneNum' ");
	switch($type) {
			case 'Hood' : hood($block,$hood,$columns,$servername,$username,$password,$log);break;
			case 'Block' : unread($block,$hood,$columns,$servername,$username,$password,$log);break;
	}

function hood($block,$hood,$columns,$servername,$username,$password,$log) {
	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
		$log->info("Connection is established with the database.");
		$stmt = $dbh->prepare("CALL hood_users(?)");
		if ($stmt->execute(array($hood))) {
				$users = array();
	  		$results = $stmt->fetchAll();
				foreach ($results as $row ) {
					 $tmp = array();
					 foreach($columns as $column ) {
						$tmp[$column] = $row[$column];
					 }
					 $data[]=$tmp;
				}
				echo json_encode($data);
	 	}
		else {
			  echo "No Hood Users";
		}
		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}
}

function block($block,$hood,$columns,$servername,$username,$password,$log) {
	try {
		$dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
		$log->info("Connection is established with the database.");
		$stmt = $dbh->prepare("CALL block_users(?,?)");
		if ($stmt->execute(array($block,$hood))) {
				$users = array();
	  		$results = $stmt->fetchAll();
				foreach ($results as $row ) {
					 $tmp = array();
					 foreach($columns as $column ) {
						$tmp[$column] = $row[$column];
					 }
					 $data[]=$tmp;
				}
				echo json_encode($data);
	 	}
		else {
			  echo "No Block Users";
		}
		$stmt = null; // doing this is mandatory for connection to get closed
		$dbh = null;
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		$log->error("Error!: " . $e->getMessage() . "<br/>");
	}
}

?>
