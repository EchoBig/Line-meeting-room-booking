<?php
  $dbhost = 'localhost';
  $dbname = 'xxx';
  $dbchar = 'utf8';
  $dbuser = 'xxx';
  $dbpass = 'xx';

  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {

    $host = "mysql:host={$dbhost};dbname={$dbname};charset={$dbchar}";
    $dbcon = new PDO($host, $dbuser, $dbpass, $options);

    // echo "Connect Successfully. Host info: " . 
    // $dbcon->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS"));

  } catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
  }

?>