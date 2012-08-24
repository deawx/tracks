<?php
Header("Content-Type: image/png");

require_once("lib.php");

if (isset($_GET["trackID"])) {
	$trackID = $_GET["trackID"];
	$req = $db->query("SELECT tableName FROM track_tables_info WHERE ID = $trackID");
	$trackTable = $req["rows"][0]["tableName"];
	$req = $db->query("SELECT time, ele FROM $trackTable ORDER BY time");
	$plot = "";
	foreach ($req["rows"] as $row) {
		$time = str_replace(" ", ".", $row["time"]);
		$plot .= $time . " " . $row["ele"] . "\n";
	}
#	echo $plot;
	$descriptors = array(
		0 => array("pipe", "r"), // stdin
		1 => array("pipe", "w"), // stdout
		2 => array("pipe", "w"), // stderr
	);
	$env = array("GDFONTPATH" => "/usr/share/fonts/liberation", "GNUPLOT_DEFAULT_GDFONT" => "LiberationSans-Regular");
	$cmd = GNUPLOT . " " . GNUPLOT_ELEVATION_SCRIPT;
	$ps = proc_open($cmd, $descriptors, $pipes, null, $env);
	if (is_resource($ps)) {
		fwrite($pipes[0], $plot);
		fclose($pipes[0]);
		echo stream_get_contents($pipes[1]);
	#	echo stream_get_contents($pipes[2]);
		fclose($pipes[1]);
		fclose($pipes[2]);
		$returnValue = proc_close($ps);
		#echo "Finished ($returnValue)";
	} else {
		echo "failed to proc_open!";
	}
} else {
	header("Location: index.php");
}
?>
