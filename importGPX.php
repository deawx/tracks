<?php
session_start();

require_once("lib.php");

// Not logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
	header("Location: index.php");
}

// No file to upload
if (!isset($_FILES["userfile"])) {
	header("Location: index.php");
}

$tmpFileName = $_FILES["userfile"]["tmp_name"];
if ($_FILES["userfile"]["error"] != 0) {
	switch ($_FILES["userfile"]["error"]) {
		case UPLOAD_ERR_INI_SIZE:
			echo "File is too big! Exceeds php limits";
			break;
		case UPLOAD_ERR_FORM_SIZE:
			echo "File is too big! Exceeds html limits";
			break;
		case UPLOAD_ERR_PARTIAL:
			echo "Failed to receive complete file";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "No file uploaded";
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			echo "Unable to upload; tmp dir is missing";
			break;
		case UPLOAD_ERR_CANT_WRITE:
			echo "Can't write to disk";
			break;
		case UPLOAD_ERR_EXTENSION:
			echo "A php ext stopped the upload";
			break;
		default:
			echo "Unknown fatal upload error occured";
			break;
	}
	exit;
}

if (!is_uploaded_file($tmpFileName)) {
	echo "You trickster!";
	exit;
}

libxml_use_internal_errors(true);
$gpx = new SimpleXMLElement(file_get_contents($tmpFileName));
if (!$gpx) {
	echo "Failed loading gpx<br><pre>";
	foreach(libxml_get_errors() as $error) {
		echo "\t", $error->message;
	}
	echo "</pre>";
	exit;
}

$trackPt = array();
$index = 0;

$trackName = (string) $gpx->trk->name;
$trackTime = strtotime($trackName);
$numOfTracks = (int) $gpx->trk->number;

$res = db_query(sprintf("INSERT INTO gps.tracks (trackName, trackDate) VALUES ('%s', FROM_UNIXTIME(%d))", mysql_real_escape_string($trackName), $trackTime));
if (!$res) {
	exit;
}

$trackID = mysql_insert_id();
if ($trackID === false) {
	echo "Error inserting track into database";
	exit;
}

foreach ($gpx->trk->trkseg->trkpt as $key => $value) {
	$trackTime = strtotime((string) $value->time);
	$trackLat = (float) $value["lat"];
	$trackLon = (float) $value["lon"];
	$trackElevation = (float) $value->ele;
	$res = db_query(sprintf("INSERT INTO gps.trackPoints (id_tracks, trackPointDate, elevation, latitude, longitude) VALUES (%d, FROM_UNIXTIME(%d), %f, %f, %f)", $trackID, $trackTime, $trackElevation, $trackLat, $trackLon));
	if (!$res) {
		exit;
	}
}
header("Location: editTrack.php?trackID=$trackID");
?>
