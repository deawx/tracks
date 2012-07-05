<?php
require_once("lib.php");
$colorWheel = array(
	"#FF0000", 
	"#00FF00", 
	"#0000FF",
	"#FFFF00",
	"#FF00FF",
	"#FFFFFF",
	"#00FFFF"
	);

header ("Content-type: text/javascript");
if (isset($_GET["multiTracks"])) {
	$multiTracks = json_decode(base64_decode($_GET["multiTracks"]), true);
	$tracks = $multiTracks["multitracks"];
	$js = "";
	foreach ($tracks as $trackID) {
		$color = next($colorWheel);
		if ($color === false) {
			$color = reset($colorWheel);
		}
		$req = db_query(sprintf("SELECT id as trackID, trackName, trackDescr, UNIX_TIMESTAMP(trackDate) as trackDate FROM gps.tracks tracks WHERE id = %d", $trackID));
		$row = mysql_fetch_assoc($req);
		$trackName = $row["trackName"];		
		 
		$req = db_query(sprintf("SELECT latitude, longitude, UNIX_TIMESTAMP(trackPointDate) as trackPointDate, elevation FROM gps.trackPoints WHERE id_tracks = %d ORDER BY trackPointDate", $trackID));
		$midPointRow = floor(mysql_num_rows($req) / 2);
		$i = 0;
		$js .= "var flightPlanCoordinates$trackID = [";		
		while ($row = mysql_fetch_assoc($req)) {
			$js .= "new google.maps.LatLng({$row["latitude"]}, {$row["longitude"]}),";
			if ($i == 0) {
				$startLat = $row["latitude"];
				$startLon = $row["longitude"];
				$startDate = sprintf("%s: %s (%f, %f)", date("c", $row["trackPointDate"]), $trackName, $startLat, $startLon);
				$startEle = sprintf("%.2f", $row["elevation"] * FEET_PER_METER);
			}
			if (++$i == $midPointRow) {
				$midLat = $row["latitude"];
				$midLon = $row["longitude"];
			}
			$endLat = $row["latitude"];
			$endLon = $row["longitude"];
			$endDate = sprintf("%s: %s (%f, %f)", date("c", $row["trackPointDate"]), $trackName, $endLat, $endLon);
			$endEle = sprintf("%.2f", $row["elevation"] * FEET_PER_METER);			
		}
		$js = substr($js, 0, -1);
		$js .= "];";
		$js .= <<< JS
var flightPath$trackID = new google.maps.Polyline({
	path: flightPlanCoordinates$trackID,
	strokeColor: "$color",
	strokeOpacity: 1.0,
	strokeWeight: 4
});
flightPath$trackID.setMap(map);

var startLatLng$trackID = new google.maps.LatLng($startLat, $startLon);
var endLatLng$trackID = new google.maps.LatLng($endLat, $endLon);

var endMarker$trackID = new google.maps.Marker({
	position: endLatLng$trackID, 
	map: map,
	title:"End: $endDate"
});

var startMarker$trackID = new google.maps.Marker({
	position: startLatLng$trackID, 
	map: map,
	title:"Start: $startDate"
});		
JS;
	}
} else {
	echo "missing trackID param!";
	exit;
}

echo <<< JS
function initialize() {
	var centerLatLng = new google.maps.LatLng($midLat, $midLon);
	var myOptions = {
		zoom: 12,
		center: centerLatLng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scaleControl: true,
		overviewMapControl: true,
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID],
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		}
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	// Required: add the MyTopo layer to the map type database   
	//  for Google Maps, also adds it to the control.  
	trimble.myTopo.init(map);
	// Optional: set MyTopo maps as the current map type  
	map.setMapTypeId(trimble.myTopo.MapTypeId.Topo);
	$js
	
	
}
JS;
?>