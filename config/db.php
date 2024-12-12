<?php
    echo '<h1>Hello DB</h1>';
   $host = 'localhost';
   $db = 'itthink';
   $user = 'root'; 
   $pass = 'change06'; 

   try {
       $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //    echo 'pass';
   } catch (PDOException $e) {
       echo "Connection failed: " . $e->getMessage();
    //    echo 'dont pass';
   }
?>