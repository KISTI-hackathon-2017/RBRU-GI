<?php
include("capabilities_kisti.php");

// {name, title, abstract}
$info = array(
	"base_url"=>"http://127.0.0.1/wfs/?",
	"ns_url"=>"http://org.rbru.ac.th/~gi",
);

// layers
$layers = array();
$layers[] = array("airtemp","Air temperature","Celcius");
$layers[] = array("noise","noise","Noise level (dB)");
$layers[] = array("state","Taxi's state","{0, 1, 2, 3, 4}");
$layers[] = array("speed","Speed (unknown unit)");
$layers[] = array("vibration","vibration","Gyroscope (x, y, z) Mg");
$layers[] = array("pressure","air pressure","hPa");
$layers[] = array("pm25","pm2.5","microgram");

echo get_capabilities($info, $layers);


/**
 *
 */
function get_capabilities($info, $layers) {
	$ret = file_get_contents("getcapabilities_kisti_2.0.xml");	
	
	$features = "";
	foreach($layers as $layer) {
		$features .= "<FeatureType xmlns:rbru=\"" . $info["ns_url"] . "\">";
			$features .= "<Name>" . $layer[0] . "</Name>";
			$features .= "<Title>" . $layer[1] . "</Title>";
			$features .= "<Abstract>" . $layer[2] . "</Abstract>";
			$features .= "<DefaultSRS>EPSG:4326</DefaultSRS>";
			$features .= "<ows:WGS84BoundingBox>";
				$features .= "<ows:LowerCorner>125.071030 34.194136</ows:LowerCorner>";
				$features .= "<ows:UpperCorner>130.718003 38.613057</ows:UpperCorner>";
			$features .= "</ows:WGS84BoundingBox>";
			$features .= "<OutputFormats>";
				$features .= "<Format>text/xml; subtype=gml/3.1.1</Format>";
			$features .= "</OutputFormats>";
		$features .= "</FeatureType>";
	}
	
	$ret = str_replace('__FEATURE_TYPE_LIST__', $features, $ret);
	
	return $ret;
}
?>