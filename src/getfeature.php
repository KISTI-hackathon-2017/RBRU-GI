<?php
// Basic 
$config = array(
	"time_start"=>"2017-10-01 00:00:00",
	"time_end"=>"2017-11-01 23:59:59",
	"node_id"=>"IK1033",
	"max_feat"=>30000,
	"bbox"=>"",
	"time"=>"",
);

// Get service
$_GET_lower = array_change_key_case($_GET);
$service = $_GET_lower['service']; // show be WFS
$version = $_GET_lower['version']; // show be WFS
$request = strtolower($_GET_lower['request']); // show be WFS
$type_names = $_GET_lower['typenames'];
$bbox = $_GET_lower['bbox'];
$time = $_GET_lower['time'];

//$typename = explode(':', $typename)[1];
$bbox = explode(',', $bbox);
$time = explode('/', $time);

// TEST : BBOX
// min: 35.877881, 128.577621
// max: 35.887182, 128.598435
$bbox[0] = 35.877881; // min lat
$bbox[1] = 128.577621; // min lon
$bbox[2] = 35.887182; // max lat
$bbox[3] = 128.598435; // max lon
$bbox = "";
if($bbox != "") {
	$config["bbox"]  = "";
	$config["bbox"] .= "AND (lat IS NOT NULL) \n";
	$config["bbox"] .= "AND (lng IS NOT NULL) \n";
	$config["bbox"] .= "AND (lat >= " . $bbox[0] . " AND lat <= " . $bbox[2]. ") \n";
	$config["bbox"] .= "AND (lng >= " . $bbox[1] . " AND lng <= " . $bbox[3]. ") \n";
}


switch($type_names) {
	case 'airtemp': $data = get_feature_airtemp($config, $bbox, $time); break;
	case 'noise': $data = get_feature_noise($config, $bbox, $time); break;
	case 'speed': $data = get_feature_speed($config, $bbox, $time); break;
	case 'state': $data = get_feature_state($config, $bbox, $time); break;
	case 'vibration': $data = get_feature_vibration($config, $bbox, $time); break;
	case 'pressure': $data = get_feature_pressure($config, $bbox, $time); break;
	case 'pm25': $data = get_feature_pm25($config, $bbox, $time); break;
	default: $data = ""; break;
}

echo $data;

/**
 *
 */
function get_feature_airtemp($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "temp_value AS val \n";
	$sql .= "FROM sensorParser \n";
	$sql .= "WHERE (lat IS NOT NULL AND lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];

	$results = query_db($sql);
	
	foreach($results as $row) {
		$features .= "<ms:airtemp fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
				$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
			$features .= "<ms:msGeometry>";
				$features .= "<gml:Point srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
				$features .= "</gml:Point>";
			$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:value>" . $row["val"] . "</ms:value>";
		$features .= "</ms:airtemp>";
	}
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}

/**
 *
 */
function get_feature_pressure($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "pres_value AS val \n";
	$sql .= "FROM sensorParser \n";
	$sql .= "WHERE (lat IS NOT NULL AND lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];

	$results = query_db($sql);
	
	foreach($results as $row) {
		$features .= "<ms:pressure fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
				$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
			$features .= "<ms:msGeometry>";
				$features .= "<gml:Point srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
				$features .= "</gml:Point>";
			$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:value>" . $row["val"] . "</ms:value>";
		$features .= "</ms:pressure>";
	}
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}

/**
 *
 */
function get_feature_noise($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "mcp_value AS val \n";
	$sql .= "FROM sensorParser \n";
	$sql .= "WHERE (lat IS NOT NULL AND lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];

	$results = query_db($sql);
	
	foreach($results as $row) {
		$features .= "<ms:noise fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
				$features .= "<ms:msGeometry>";
					$features .= "<gml:Point srsName=\"EPSG:4326\">";
						$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
					$features .= "</gml:Point>";
				$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:value>" . $row["val"] . "</ms:value>";
		$features .= "</ms:noise>";
	}
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}

/**
 *
 */
function get_feature_speed($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "spd as val \n";
	$sql .= "FROM sensorParser  \n";
	$sql .= "WHERE (lat IS NOT NULL and lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];

	$results = query_db($sql);
	
	foreach($results as $row) {
		$features .= "<ms:speed fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
			$features .= "<ms:msGeometry>";
				$features .= "<gml:Point srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
				$features .= "</gml:Point>";
			$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:value>" . $row["val"] . "</ms:value>";
		$features .= "</ms:speed>";
	}
	//echo $features;
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}

/**
 *
 */
function get_feature_state($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "spd, \n";
		$sql .= "temp_value AS val \n";
	$sql .= "FROM sensorParser \n";
	$sql .= "WHERE (lat IS NOT NULL and lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];
	
	$results = query_db($sql);
	
	$features = '';
	$lat = $results[0]['lat'];
	$lng = $results[0]['lng'];
	$t   = $results[0]['timestamp']; // start time
	$spd = $results[0]['spd']; // start time
	$taxi_state = 1;
	$dtc = 0;
	foreach($results as $row) {
		
		$dx = abs($lat - $row['lat']);
		$dy = abs($lng - $row['lng']);
		$ds = abs($spd - $row['spd']); // delta speed
		$dt = abs($t - $row['timestamp']); // delta time
		
		// Detect slow speed movement
		if($row['spd'] < 0.5) {
			// Taxi has just stopped. Start counting.
			if($spd > 0.5) {// previous speed
				$t = $row['timestamp'];
				$taxi_state = 1; // just stopped (state = 1)
			} else {
				// Taxi is already stopped for a moment.
				if($dt > 150) { // 150 seconds
					// Too long
					$taxi_state = 4; // stop
				} else {
					// Not so long
					$taxi_state = 2; // move
				}
			}
			
			//$taxi_state = $dt; // stop
		} else {
			if($spd < 0.5) {
				// just start moving
				$taxi_state = 3; // move
			} else {
				// still moving
				$taxi_state = 0; // move
			}
			
			$t = $row['timestamp'];
		}
		$spd = $row['spd'];
		$lat = $row['lat'];
		$lng = $row['lng'];
		
		$features .= "<ms:state fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
			$features .= "<ms:msGeometry>";
				$features .= "<gml:Point srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
				$features .= "</gml:Point>";
			$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:t>" . $t . "</ms:t>";
			$features .= "<ms:dt>" . $dt . "</ms:dt>";
			$features .= "<ms:speed>" . $row['spd'] . "</ms:speed>";
			$features .= "<ms:value>" . $taxi_state . "</ms:value>";
		$features .= "</ms:state>";
	}
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}

/**
 * Vibration.
 */
function get_feature_vibration($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "vbr_value AS val \n";
	$sql .= "FROM sensorParser \n";
	$sql .= "WHERE (lat IS NOT NULL and lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];

	$results = query_db($sql);
	
	foreach($results as $row) {
		// magnitude
		$vbr = explode(';', $row['val']);
		$mag = sqrt(($vbr[0]*$vbr[0]) + ($vbr[1]*$vbr[1]) + ($vbr[2]*$vbr[2]));
		
		$features .= "<ms:vibration fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
			$features .= "<ms:msGeometry>";
				$features .= "<gml:Point srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
				$features .= "</gml:Point>";
			$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:value>" . $mag . "</ms:value>";
		$features .= "</ms:vibration>";
	}
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}

/**
 * Vibration.
 */
function get_feature_pm25($config, $time) {
	$sql = "";
	$sql .= "SELECT \n";
		$sql .= "id, \n";
		$sql .= "node_id, \n";
		$sql .= "UNIX_TIMESTAMP(timestamp) AS timestamp, \n";
		$sql .= "DATE(timestamp) AS ts_date, \n";
		$sql .= "TIME(timestamp) AS ts_time, \n";
		$sql .= "HOUR(timestamp) AS ts_hour, \n";
		$sql .= "lat, lng, \n";
		$sql .= "pm2_5_value AS val \n";
	$sql .= "FROM sensorParser \n";
	$sql .= "WHERE (lat IS NOT NULL and lng IS NOT NULL) \n";
		$sql .= "AND (TIMESTAMP BETWEEN '" . $config['time_start'] . "' AND '" . $config['time_end'] . "') \n";
		$sql .= $config['bbox'];
	$sql .= "ORDER BY timestamp ASC \n";
	$sql .= "LIMIT " . $config['max_feat'];

	$results = query_db($sql);
	
	foreach($results as $row) {
		$features .= "<ms:pm25 fid=\"" . $row["id"] . "\">";
			$features .= "<gml:boundedBy>";
				$features .= "<gml:Box srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>100.508796,13.865581 100.510194,13.866919</gml:coordinates>";
				$features .= "</gml:Box>";
			$features .= "</gml:boundedBy>";
			$features .= "<ms:msGeometry>";
				$features .= "<gml:Point srsName=\"EPSG:4326\">";
					$features .= "<gml:coordinates>" . $row["lng"] . "," . $row["lat"] . "</gml:coordinates>";
				$features .= "</gml:Point>";
			$features .= "</ms:msGeometry>";
			$features .= "<ms:id>" . $row["id"] . "</ms:id>";
			$features .= "<ms:nodeid>" . $row["node_id"] . "</ms:nodeid>";
			$features .= "<ms:timestamp>" . $row["timestamp"] . "</ms:timestamp>";
			$features .= "<ms:date>" . $row["ts_date"] . "</ms:date>";
			$features .= "<ms:time>" . $row["ts_time"] . "</ms:time>";
			$features .= "<ms:hour>" . $row["ts_hour"] . "</ms:hour>";
			$features .= "<ms:value>" . $row['val'] . "</ms:value>";
		$features .= "</ms:pm25>";
	}
	
	$ret = file_get_contents("getfeature_kisti.xml");
	$ret = str_replace('__FEATURE_TYPE_DATA__', $features, $ret);
	
	// Done
	echo $ret;
}
?>