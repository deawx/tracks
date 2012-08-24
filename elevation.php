<?php
require_once("lib.php");

if (isset($_GET["trackID"])) {
	$trackID = $_GET["trackID"];
	$elevationProfile = "<img src=\"eleProfile.php?trackID=$trackID\">";
	$req = $db->query("SELECT tableName, trackName, trackDate, userDescr FROM track_tables_info WHERE ID = $trackID");
	$tableName = $req["rows"][0]["tableName"];
	$trackName = $req["rows"][0]["trackName"]; 
	$trackDate = $req["rows"][0]["trackDate"]; 
	$trackDescr = $req["rows"][0]["userDescr"]; 
	$info = "Track: $trackName<br>";
	$info .= "Date: $trackDate<br>";
	$info .= "Descr: $trackDescr<br>";

	$req = $db->query("SELECT MAX(ele) as maxElev, MIN(ele) as minElev, TIMESTAMPDIFF(SECOND, MIN(time), MAX(time)) as timeDiff FROM $tableName");
	$maxElev = $req["rows"][0]["maxElev"];
	$minElev = $req["rows"][0]["minElev"];
	$timeDiff = $req["rows"][0]["timeDiff"];

	$info .= "Min Elevation: " . round($minElev, 2) . " meters / " . round(Util::meters2feet($minElev), 2) . " feet<br>";
	$info .= "Max Elevation: " . round($maxElev, 2) . " meters / " . round(Util::meters2feet($maxElev), 2) . " feet<br>";
	$info .= "Elevation Change: " . round(($maxElev - $minElev), 2) . " meters / " . round(Util::meters2feet($maxElev - $minElev), 2) . " feet<br>";
	$duration = Util::seconds2HumanTime($timeDiff);
	$info .= sprintf("Hike duration %02d:%02d:%02d<br>", $duration["hours"], $duration["minutes"], $duration["seconds"]);

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
