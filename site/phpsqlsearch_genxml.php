<?php  
/**
*
* Location Finder
*
* Location finder is a component for Joomla which integrates a location table 
* with Google Maps for finding the nearest location.
*
* Joomla 1.5, Google Maps API v3
*
* Copyright (c) 2009, Joe Julian
*                     joe@julianfamily.org
*
* This is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

require("../../configuration.php");
// Fixes the encoding to uf8
function fixEncoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
} // fixEncoding 

$config = new JConfig();

// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];

// Start XML file, create parent node
$dom = new DOMDocument('<?xml version="1.0" encoding="utf-8"?><root/>');
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a mySQL server
$connection=mysql_connect ($config->host, $config->user, $config->password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db($config->db, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

// Search the rows in the dbprefix.locationfinder table
$query = sprintf("SELECT CONCAT_WS(' ',street,city,state,postal_code,country) AS address," .
		         "       name, " .
		         "       street as addr1, " .
		         "       CONCAT(city, ', ', state, ' ', postal_code) as addr2, " .
		         "       country, " .
		         "       lat, " .
		         "       lng, " .
		         "       description," .
		         "       phone," .
		         "       email," .
		         "       url," .
		         "       ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance " .
		         "FROM " . $config->dbprefix . "locationfinder " .
				 "HAVING distance < '%s' " .
				 "ORDER BY distance LIMIT 0 , 20",
                 mysql_real_escape_string($center_lat),
                 mysql_real_escape_string($center_lng),
                 mysql_real_escape_string($center_lat),
                 mysql_real_escape_string($radius));
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name", fixEncoding($row['name']));
  $newnode->setAttribute("address", fixEncoding($row['address']));
  $newnode->setAttribute("addr1",fixEncoding($row['addr1']));
  $newnode->setAttribute("addr2",fixEncoding($row['addr2']));
  $newnode->setAttribute("country",fixEncoding($row['country']));
  $newnode->setAttribute("phone",fixEncoding($row['phone']));
  $newnode->setAttribute("email",fixEncoding($row['email']));
  $newnode->setAttribute("url",fixEncoding($row['url']));
  $newnode->setAttribute("desc",fixEncoding($row['description']));
  $newnode->setAttribute("lat", fixEncoding($row['lat']));
  $newnode->setAttribute("lng", fixEncoding($row['lng']));
  $newnode->setAttribute("distance", fixEncoding($row['distance']));
}

echo $dom->saveXML($dom->documentElement);
?>
