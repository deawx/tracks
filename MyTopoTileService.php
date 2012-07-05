<?php
$mytopo_partnerID = 12389;
$mytopo_secretKey = "cc9839a0-be60-4b9a-8e15-2d53d2de4567";
$mytopo_clientIP;

if ( isset($_SERVER["REMOTE_ADDR"]) )    { 
   $mytopo_clientIP=$_SERVER["REMOTE_ADDR"]; 
} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    { 
   $mytopo_clientIP=$_SERVER["HTTP_X_FORWARDED_FOR"]; 
} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    { 
   $mytopo_clientIP=$_SERVER["HTTP_CLIENT_IP"]; 
} 

$mytopo_hash = md5($mytopo_secretKey.$mytopo_clientIP)
?>
