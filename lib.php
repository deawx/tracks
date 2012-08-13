<?php
require_once("db.php");

define ("FEET_PER_METER", 3.2808399);
define ("PIC_FEED", "https://picasaweb.google.com/data/feed/base/user/104513162711957846602/albumid/5722183446880050193?alt=rss&kind=photo&hl=en_US");
define ("SECRET", "p1ss0ff");
define ("INI_FILE", "/var/www/tracks.ini");

if (!file_exists(INI_FILE)) {
	echo "INI file (" . INI_FILE . ") does not exist!";
	exit;
}

$iniOptions = parse_ini_file(INI_FILE, true);
if ($iniOptions === false) {
	echo "Failed to parse INI file (" . INI_FILE . ")!";
	exit;
}

if (!isset($iniOptions["mysql"])) {
	echo "INI file (" . INI_FILE . ") missing section '[mysql]'.";
	exit;
}

$mysqlServer = null;
$mysqlDatabase = null;
$mysqlUser = null;
$mysqlPasswd = null;
foreach ($iniOptions["mysql"] as $option => $value) {
	switch ($option) {
		case "server":
			$mysqlServer = $value;
			break;
		case "database":
			$mysqlDatabase = $value;
			break;
		case "user":
			$mysqlUser = $value;
			break;
		case "password":
			$mysqlPassword = $value;
			break;
		default:
			echo "Unknown key/value ($option/$value) pair specified in ini file (" . INI_FILE .").";
			exit;
	}
}

if (is_null($mysqlServer)) {
	echo "mysql server is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (is_null($mysqlDatabase)) {
	echo "mysql database is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (is_null($mysqlUser)) {
	echo "mysql user is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (is_null($mysqlPassword)) {
	echo "mysql password is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

$analytics = "<script type=\"text/javascript\" src=\"/analytics.js\"></script>";
$google_api_id = "AIzaSyDOW5DPTHvc5b8aO0zttUeo3IwVqOKVE0g";
$db = new db($mysqlServer, $mysqlUser, $mysqlPassword, $mysqlDatabase);

function array_dump($array) {
	echo "<pre>" . print_r($array, true) . "</pre>";
}
?>
