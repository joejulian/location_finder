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

header("Content-type: text/xml");
// ini_set("display_errors", false);

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

// PHP_VERSION_ID is available as of PHP 5.2.7, if our version is lower than that, then emulate it
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

// Start XML file, create parent node
if( ( 40200 <= PHP_VERSION_ID ) && ( PHP_VERSION_ID < 50000 ) ) {
    $dom = domxml_new_doc("1.0");
    $dom_createelement_model = "create_element";
    $dom_appendchild_model = "append_child";
    $dom_setattribute_model = "set_attribute";
} elseif (PHP_VERSION_ID >= 50000) {
    $dom = new DOMDocument('<?xml version="1.0" encoding="utf-8"?><root/>');
    $dom_createelement_model = "createElement";
    $dom_appendchild_model = "appendChild";
    $dom_setattribute_model = "setAttribute";
} else {
    die("<b>Error: </b>PHP Version not supported");
}
$node = $dom->$dom_createelement_model("markers");
$parnode = $dom->$dom_appendchild_model($node);

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

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  $node = $dom->$dom_createelement_model("marker");
  $newnode = $parnode->$dom_appendchild_model($node);
  $newnode->$dom_setattribute_model("name", fixEncoding($row['name']));
  $newnode->$dom_setattribute_model("address", fixEncoding($row['address']));
  $newnode->$dom_setattribute_model("addr1",fixEncoding($row['addr1']));
  $newnode->$dom_setattribute_model("addr2",fixEncoding($row['addr2']));
  $newnode->$dom_setattribute_model("country",fixEncoding($row['country']));
  $newnode->$dom_setattribute_model("phone",fixEncoding($row['phone']));
  $newnode->$dom_setattribute_model("email",fixEncoding($row['email']));
  $newnode->$dom_setattribute_model("url",fixEncoding($row['url']));
  $newnode->$dom_setattribute_model("desc",fixEncoding($row['description']));
  $newnode->$dom_setattribute_model("lat", fixEncoding($row['lat']));
  $newnode->$dom_setattribute_model("lng", fixEncoding($row['lng']));
  $newnode->$dom_setattribute_model("distance", fixEncoding($row['distance']));
}

if( ( PHP_VERSION_ID < 50000 ) ) {
    $xmlfile = $doc->dump_mem();
    echo $xmlfile;
} else {
    echo $dom->saveXML($dom->documentElement);
}
?>
