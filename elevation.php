<?php
require_once("lib.php");

if (isset($_GET["trackID"])) {
	$trackID = $_GET["trackID"];
	$elevationProfile = "<img src=\"eleProfile.php?trackID=$trackID\">";
	$req = $db->query("SELECT trackName, trackDate, userDescr FROM track_tables_info WHERE ID = $trackID");
	$trackName = $req["rows"][0]["trackName"]; 
	$trackDate = $req["rows"][0]["trackDate"]; 
	$trackDescr = $req["rows"][0]["userDescr"]; 
	$info = "Track: $trackName<br>Date: $trackDate<br>Descr: $trackDescr<br>";
} else {
	header("Location: index.php");
}

echo <<<CONTENT
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<title>Hikes and things!</title>
		<link href="default.css" rel="stylesheet" type="text/css">
		$analytics
	</head>
	<body>
		$info
		<div>[<a href="index.php">go back</a>]</div>
		<div>$elevationProfile</div>
	</body>
</html>
CONTENT;
?>
