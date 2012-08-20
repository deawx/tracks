<?php
session_start();

// Not logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
	header("Location: index.php");
}

require_once("lib.php");

if (isset($_GET["modify"])) {
	if (isset($_POST["newTrackTag"]) && trim($_POST["newTrackTag"]) != "") {
		$req = $db->query(sprintf("INSERT INTO track_tags (trackTag) VALUES ('%s')", $db->escapeString($_POST["newTrackTag"])));
		if (!$req) {
			echo mysql_error();
		}
	}
	
	if (isset($_POST["delete"])) {
		foreach ($_POST["delete"] as $trackID => $flag) {
			$req = $db->query(sprintf("DELETE FROM track_tags WHERE id = %d", $db->escapeString($trackID)));
		}
	}
	
	if (isset($_POST["update"])) {
		foreach ($_POST["update"] as $trackID => $newValue) {
			if (trim($newValue) != "") {
				$req = $db->query(sprintf("UPDATE track_tags SET trackTag = '%s' WHERE id = %d", $db->escapeString($newValue), $db->escapeString($trackID)));
			}
		}
	}
	//echo "<pre>";
	//print_r($_POST);
	//echo "</pre>";
}


$req = $db->query("SELECT * FROM track_tags");

$tracks = '<form action="?modify" method="post">';
if ($req) {
	foreach($req["rows"] as $row) {
		$trackTypeID = $row["id"];
		$trackTag = $row["trackTag"];
		$tracks .= <<< HTML
	<input type="checkbox" name="delete[$trackTypeID]"><input type="text" value="$trackTag" name="update[$trackTypeID]"><br>
HTML;
	}
}

$tracks .= <<< HTML
	<label for="newTrackTag">New Tag</label><input type="text" name="newTrackTag"><br>
	<input type="submit" value="update">
</form>
HTML;
	

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
	<body">
		$tracks
		[<a href="index.php">go back</a>]
	</body>
</html>
CONTENT;
?>