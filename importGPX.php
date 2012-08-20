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

$fileMD5 = md5_file($tmpFileName);
$execStr = OGR2OGR . " -f \"MySQL\" MYSQL:\"" . MYSQL_DBASE . ",user=" . MYSQL_USER . ",password=" . MYSQL_PASSWORD . "\" -lco engine=MYISAM -nln track_$fileMD5 -overwrite $tmpFileName";
exec($execStr, $execOutput, $returnValue);
if ($returnValue != 0) {
	echo "Exec failed..." . Util::eol();
	foreach ($execOutput as $line) {
		echo $line . Util::eol();
	}
	echo Util::eol() . "You may need to clean up..." . Util::eol();
	exit;
}

$req = $db->query("SELECT OGR_FID, time FROM track_$fileMD5 ORDER BY OGR_FID limit 1");
$timeStamp = $req["rows"][0]["time"];
$db->query("INSERT INTO track_tables_info (tableName, trackDate) VALUES ('track_$fileMD5', '$timeStamp')");
$req = $db->query("SELECT id FROM track_tables_info WHERE tableName = 'track_$fileMD5'");
$trackID = $req["rows"][0]["id"];

header("Location: editTrack.php?trackID=$trackID");
?>
