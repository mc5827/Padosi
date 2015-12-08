<?php
// Insert the path where you unpacked log4php
include('..\apache-log4php-2.3.0\src\main\php\Logger.php');

// Tell log4php to use our configuration file.
Logger::configure('..\config.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('Padosi');

$selected=array();
//$selected = $_POST["selected"]; //pulls value of radio button
echo "Padosi";


//$username = $_GET["username"]; //pulls value of radio button
//$typeOfFeed = $_GET["typeOfFeed"]; //pulls value of radio button

$servername = 'localhost';
$username = 'root';
$password = '';

//session_start();
//$_SESSION['username'] = $phoneNum;
$log->info("Storing the session variable 'phoneNum' ");

try {
    $dbh = new PDO('mysql:host=localhost;port=3306;dbname=dbproject', $username, $password);
	$log->info("Connection is established with the database.");
  $stmt = $dbh->prepare("CALL user_count()");
  //$value = 'hello';
  //$stmt->bindParam(1, $value, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 4000);

  // call the stored procedure
  if($stmt->execute()) {
  		$results = $stmt->fetchAll();
  		foreach ($results as $row ) {
  			 echo $row['count(*)'];
  		}
  	}
  	else {
  		$log->info("No Users");
  		echo "No USers";
  	}



	$stmt = null; // doing this is mandatory for connection to get closed
  $dbh = null;
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
	$log->error("Error!: " . $e->getMessage() . "<br/>");
}



?>
