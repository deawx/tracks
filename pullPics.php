<?php
require_once("lib.php");

libxml_use_internal_errors(true);
$pics = new SimpleXMLElement(file_get_contents(PIC_FEED));
if (!$pics) {
	echo "Failed loading gpx<br><pre>";
	foreach(libxml_get_errors() as $error) {
		echo "\t", $error->message;
	}
	echo "</pre>";
	exit;
}
echo "<pre>";
var_dump($pics);
echo "</pre>";


?>