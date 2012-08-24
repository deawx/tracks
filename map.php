<?php
require_once("lib.php");
require_once("MyTopoTileService.php");

if (isset($_POST["tracks"])) {
	$multiTracks["multitracks"] = array();
	foreach ($_POST["tracks"] as $trackID => $flag) {
		$multiTracks["multitracks"][] = $trackID;
	}
	$encMultiTracks = base64_encode(json_encode($multiTracks));
	$tracksJS = "<script type=\"text/javascript\" src=\"mkmap.js.php?multiTracks=$encMultiTracks\"></script>";
	$mapCanvas = '<div id="map_canvas"></div>';	
} elseif (isset($_GET["tracks"])) {
	$encMultiTracks = $_GET["tracks"];
	$tracksJS = "<script type=\"text/javascript\" src=\"mkmap.js.php?multiTracks=$encMultiTracks\"></script>";
	$mapCanvas = '<div id="map_canvas"></div>';
} else {
	header("Location: index.php");
}

if ($db->debug()) {
	$mapCanvas = "Database calls are in debug mode... this won't work!";
}
$google_api_id = GOOGLE_API_ID;

echo <<<CONTENT
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<title>Hikes and things!</title>
		<link href="default.css" rel="stylesheet" type="text/css">
		$analytics
		<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=$google_api_id&sensor=false"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
		<script type="text/javascript" src="http://www.mytopo.com/TileService/Scripts/trimble.mytopo.v3.js?partnerID=$mytopo_partnerID&hash=$mytopo_hash"></script> 
		$tracksJS
	</head>
	<body onload="initialize()">
		<div>[<a href="index.php">go back</a>]</div>
		<div>[<a href="?tracks=$encMultiTracks">link</a>] to this map</div>
		$mapCanvas
	</body>
</html>
CONTENT;
?>
