<?php
require_once("lib.php");

if (isset($_GET["trackID"])) {
	$trackID = $_GET["trackID"];
	$elevationProfile = '<img src="eleProfile.php?trackID=$trackID">';
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
		<div>$elevationProfile</div>
	</body>
</html>
CONTENT;
?>
