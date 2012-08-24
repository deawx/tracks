<?php
//Header("Content-Type: image/png")

require_once("lib.php");

if (isset($_GET["trackID"])) {
	$trackID = $_GET["trackID"];
	$req = $db->query("SELECT tableName FROM track_tables_info WHERE ID = $trackID");
	$trackTable = $req["rows"][0]["tableName"];
	$req = $db->query("SELECT time, ele FROM $trackTable ORDER BY time");
	$plot = "";
	foreach ($req["rows"] as $row) {
		$plot .= $row["time"] . "," . $row["ele"] . "\n";
	}
	$descriptors = array(
		0 => array("pipe", "r"), // stdin
		1 => array("pipe", "w"), // stdout
		2 => array("pipe", "w"), // stderr
	);
	$cmd = "/usr/bin/gnuplot";

} else {
	header("Location: index.php");
}
?>
