<?php
session_start();

// Not logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
	header("Location: index.php");
}

require_once("lib.php");

if (isset($_GET["trackID"])) {
	$trackID = $_GET["trackID"];
	if (isset($_GET["delete"])) {
		$req = db_query(sprintf("DELETE FROM gps.tracks WHERE id = %d", mysql_real_escape_string($trackID)));
		$req = db_query(sprintf("DELETE FROM gps.trackPoints WHERE id_tracks = %d", mysql_real_escape_string($trackID)));
		echo "Deleted track id $trackID<br>";
		echo '[<a href="index.php">go back</a>]';
		exit;
	}
	
	if (isset($_POST["update"])) {
		$newTrackName = mysql_real_escape_string($_POST["trackName"]);
		$newTrackDescr = mysql_real_escape_string($_POST["trackDescr"]);
		$req = db_query(sprintf("UPDATE gps.tracks SET trackName = '%s', trackDescr = '%s' WHERE id = %d", $newTrackName, $newTrackDescr, mysql_real_escape_string($trackID)));
		$req = db_query(sprintf("DELETE FROM gps.trackTagsLink WHERE tracksID = %d", mysql_real_escape_string($trackID)));
		if (isset($_POST["tag"])) {
			foreach ($_POST["tag"] as $tagID => $flag) {
				$req = db_query(sprintf("INSERT INTO gps.trackTagsLink (trackTagID, tracksID) VALUES (%d, %d)", mysql_real_escape_string($tagID), mysql_real_escape_string($trackID)));
			}
		}
		
		echo "Track data updated...<br>";
	}

	
	$req = db_query("SELECT * FROM gps.trackTags");
	$trackTags = array();
	while ($row = mysql_fetch_assoc($req)) {
		$trackTags[$row["id"]] = array("tag" => $row["trackTag"], "linked" => false);
	}
	
	$req = db_query(sprintf("SELECT * FROM gps.trackTagsLink WHERE tracksID = %d", mysql_real_escape_string($trackID)));
	while ($row = mysql_fetch_assoc($req)) {
		$trackTags[$row["trackTagID"]]["linked"] = true;
	}

	$req = db_query(sprintf("SELECT id as trackID, trackName, trackDescr, UNIX_TIMESTAMP(trackDate) as trackDate FROM gps.tracks WHERE id = %d", mysql_real_escape_string($trackID)));
	$row = mysql_fetch_assoc($req);
	$trackID = $row["trackID"];
	$trackName = $row["trackName"];
	$trackDate = date("c", $row["trackDate"]);
	$trackDescr = $row["trackDescr"];
	$req = db_query(sprintf("SELECT COUNT(id) as count FROM gps.trackPoints WHERE id_tracks = %d", mysql_real_escape_string($trackID)));
	$row = mysql_fetch_assoc($req);
	$count = $row["count"];

	$trackTagChecks = "<ul>";
	foreach ($trackTags as $trackTag => $data) {
			$checked = "";
			$trackTagName = $data["tag"];
			if ($data["linked"] === true) {
				$checked = "CHECKED";
			}
			$trackTagChecks .= <<< HTML
<li><input type="checkbox" name="tag[$trackTag]" $checked>$trackTagName</li>
HTML;
	}
	$trackTagChecks .= "</ul>";
	
	$content = <<< HTML
<form name="formUpdateTrack" action="?trackID=$trackID" method="post">
	<input type="hidden" name="update">
track id: $trackID<br>
Name: <input type="text" name="trackName" value="$trackName"><br>
Date: $trackDate<br> 
Descr: <textarea name="trackDescr" rows="2" cols="20">$trackDescr</textarea><br>
Points in track: $count<br>
Tags:
$trackTagChecks
</form>
HTML;
} else {
	$content = "Missing trackID";
}
echo <<<CONTENT
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<title>Google Maps JavaScript API v3 Example: Polyline Simple</title>
		<link href="default.css" rel="stylesheet" type="text/css">
		$analytics
	</head>
	<body>
		$content
		[<a href="?trackID=$trackID&delete">delete track</a>] [<a href="javascript:document.formUpdateTrack.submit()">update</a>] [<a href="index.php">go back</a>]
	</body>
</html>
CONTENT;
?>