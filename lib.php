<?php
define ("FEET_PER_METER", 3.2808399);
define ("PIC_FEED", "https://picasaweb.google.com/data/feed/base/user/104513162711957846602/albumid/5722183446880050193?alt=rss&kind=photo&hl=en_US");
define ("SECRET", "p1ss0ff");

$analytics = "<script type=\"text/javascript\" src=\"/analytics.js\"></script>";
$google_api_id = "AIzaSyDOW5DPTHvc5b8aO0zttUeo3IwVqOKVE0g";
$db = mysql_connect("localhost", "rsigler", "2c0lorsNmh");

function db_query($query) {
	//echo "<pre>Query: $query</pre>";
	$res = mysql_query($query);
	if (!$res) {
		echo "<pre>For query:\n$query\n" . mysql_error() . "</pre>";
		return false;
	}
	return $res;
}

function array_dump($array) {
	echo "<pre>" . print_r($array, true) . "</pre>";
}
?>