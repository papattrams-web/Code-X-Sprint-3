<?php
/*$env = parse_ini_file(__DIR__ .'/../env/connect.env');

$conn = new mysqli(
    $env['servername'],
    $env['username'],
    $env['password'],
    $env['dbname']
   );

   */
   $servername="localhost";
   $username="root";
   $password="";
   $dbname="essentials";

   $conn=new mysqli($servername,$username,$password,$dbname);
   
   // Check connection
   if ($conn->connect_error) {
    // Throw exception 
    throw new Exception("Connection failed: " . $conn->connect_error);
}
