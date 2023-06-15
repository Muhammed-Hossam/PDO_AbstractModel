<?php

$connection = null;

try {
  $connection = new PDO("mysql://hostname=localhost;dbname=php_pdo", 'root', '', [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
}catch (PDOException $e) {
  
}