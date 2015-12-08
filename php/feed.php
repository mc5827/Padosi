<?php
// Insert the path where you unpacked log4php
include('..\apache-log4php-2.3.0\src\main\php\Logger.php');

// Tell log4php to use our configuration file.
Logger::configure('..\config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('Padosi');

//$user = $_GET["user"]; //pulls value of radio button
//$typeOfFeed = $_GET["typeOfFeed"]; //pulls value of radio button
$user = "sanjitha";
$typeOfFeed = "block";
$columns = array('feedId','feedSubject','feedAuthor','feedText');
$action = 'unread';

$servername = 'localhost';
$username = 'root';
$password = '';

//session_start();
//$_SESSION['username'] = $phoneNum;
$log->info("Storing the session variable 'user' ");

//if(isset($_GET['action']) && !empty($_GET['action'])) {
    //$action = $_GET['action'];
    switch($action) {
        case 'all' : all($user,$typeOfFeed,$columns,$servername,$username,$password,$log);break;
        case 'unread' : unread($user,$columns,$servername,$username,$password,$log);break;
    }
//}

function all($user,$typeOfFeed,$columns,$servername,$username,$password,$log) {
  try {
          $dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
    			$log->info("Connection is established with the database.");
  				$log->info("Get the feeds of the current block/neighbourhood based on the feed type "+$typeOfFeed);
          $stmt= $dbh->prepare('Select * from receipient natural join feed where receiverName = ? and category = ? ;');
  				$stmt->execute(array($user,$typeOfFeed));
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
        		$log->info("No current/previous feeds.");
        		echo "No current/previous feeds.";
        	}
  			$dbh = null;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
  	$log->error("Error!: " . $e->getMessage() . "<br/>");
  }

}

function unread($user,$columns,$servername,$username,$password,$log) {
  try {
          $dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
          $log->info("Connection is established with the database.");
          $log->info("Get the feeds of the current block/neighbourhood with unread messages.");
          //$stmt= $dbh->prepare('Select * from receipient natural join feed where receiverName = ? and category = ? ;');
          $stmt= $dbh->prepare('Select * from receipient natural join feed where receiverName = ? and feedReceiverStatus like "current" and feedId IN (select feedId from unreadmessage);');
          $stmt->execute(array($user));
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
            $log->info("No current/previous feeds.");
            echo "No current/previous feeds.";
          }
        $dbh = null;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
    $log->error("Error!: " . $e->getMessage() . "<br/>");
  }
}





?>
