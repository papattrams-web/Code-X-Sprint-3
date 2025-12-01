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
   $username="stephanie.klomegah";
   $dbpassword="0549833478";
   $dbname="webtech_2025A_stephanie_klomegah";

   $conn=new mysqli($servername,$username,$dbpassword,$dbname);
   
   // Check connection
   if ($conn->connect_error) {
    // Throw exception 
    throw new Exception("Connection failed: " . $conn->connect_error);
}
