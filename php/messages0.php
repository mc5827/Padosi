<?php
// Insert the path where you unpacked log4php
include('..\apache-log4php-2.3.0\src\main\php\Logger.php');

// Tell log4php to use our configuration file.
Logger::configure('..\config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('Padosi');

$selected=array();
//$selected = $_POST["selected"]; //pulls value of radio button


//$user = $_GET["user"]; //pulls value of radio button
//$feedId = $_GET["feedId"]; //pulls value of radio button
$user = "sanjitha";
$feedId = "1";
$columns = array('messageId','feedId','messageAuthor','messageText','location','messageTime');


$servername = 'localhost';
$username = 'root';
$password = '';

//session_start();
//$_SESSION['username'] = $phoneNum;
$log->info("Storing the session variable 'user' ");

try {
        $dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
  			$log->info("Connection is established with the database.");
				$log->info("Get the messages  of a feed with id  "+$feedId);
        $stmt= $dbh->prepare('select * from messages where feedid = ? ;');
				$stmt->execute(array(intval($feedId)));
				$isNotEmpty = $stmt->rowCount()>0;
        if($isNotEmpty) {
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
      		$log->info("No message for feed "+feedId);
      		echo "No message for feed "+feedId;
      	}
			$dbh = null;
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
	$log->error("Error!: " . $e->getMessage() . "<br/>");
}



?>
