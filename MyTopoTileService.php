<?php
$mytopo_partnerID = MYTOPO_PARTNER_ID;
$mytopo_secretKey = MYTOPO_SECRET_KEY;
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
