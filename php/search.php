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
//$feedId = $_GET["feedId"];
//$keyword = $_GET["keyword"]; //pulls value of radio button
$user = "mohit";
$feedId = 1;
$keyword = "mt1";
$columns = array('messageId','feedId','messageAuthor','messageText','location','messageTime');


$servername = 'localhost';
$username = 'root';
$password = 'password';

//session_start();
//$_SESSION['username'] = $phoneNum;
$log->info("Storing the session variable 'user' ");

try {
        $dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
  			$log->info("Connection is established with the database.");
				$log->info("Check if the feedid ".$feedId." is of the previous block/neighbourhood for the user ".$user);
        $stmt= $dbh->prepare('Select * from receipient where receiverName like ? and  feedId = ?  and feedReceiverStatus = "previous" ;');
				$stmt->execute(array($user,intval($feedId)));
				$feedOfThePreviousLocation = $stmt->rowCount()>0;
        if($feedOfThePreviousLocation) {
          $log->info("The feed ".$feedId." of the user ".$user." belongs to the previous block/hood : true");
          $log->info("Check if the feedauthor of the feed ".$feedId." has changed.");
          $stmt= $dbh->prepare('select * from feed join localityhistory on username = feedauthor where feedId=? and feedtime between locationStartTime and locationEndTime;');
          $stmt->execute(array(intval($feedId)));
          $authorChangedLocation = $stmt->rowCount()>0;
          if($authorChangedLocation) {
              $log->info("Feedauthor changed location : true");
              $stmt= $dbh->prepare('select * from messages,(select * from localityhistory where username=? and (block,hood) IN (select block,hood from feed join localityhistory on username = feedauthor where feedId=? and feedtime between locationStartTime and locationEndTime)) as T2 where feedid=? and messagetime between T2.locationStartTime and T2.locationEndTime and messageText like ?;');
              $stmt->execute(array($user,intval($feedId),intval($feedId),"%$keyword%"));
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
                $log->info("No messages from the old  feed "+feedId);
                echo "No messages from the old feed "+feedId;
              }
          }
          else {
              $log->info("Feedauthor changed location : false");
              $stmt= $dbh->prepare('select * from messages,(select * from localityhistory where username=? and (block,hood) IN (select block,hood from feed join registereduser on username = feedauthor where feedId=?)) as T2 where feedid=? and messagetime between T2.locationStartTime and T2.locationEndTime and messageText like ?;');
              $stmt->execute(array($user,intval($feedId),intval($feedId),"%$keyword%"));
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
          }


      	}
      	else {
            $log->info("The feed ".$feedId." of the user ".$user." belongs to the previous block/hood : false");
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
      	}
			$dbh = null;
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
	$log->error("Error!: " . $e->getMessage() . "<br/>");
}



?>
