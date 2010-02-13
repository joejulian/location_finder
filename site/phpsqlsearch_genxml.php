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
ini_set('display_errors',false);

header("Content-type: text/xml");

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', dirname(__FILE__).DS.'..'.DS.'..' );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
JPluginHelper::importPlugin('system');
$_SERVER['SCRIPT_NAME'] = dirname($_SERVER['SCRIPT_NAME']).DS.'..'.DS.'..'.DS.'dummy.php'; //hack to make JURI::base be the actual base for sef

// Get parameters from URL
$center_lat = JRequest::getVar('lat');
$center_lng = JRequest::getVar('lng');
$radius = JRequest::getVar('radius');

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

// Start the parent xml node
$node = $dom->$dom_createelement_model("markers");
$parnode = $dom->$dom_appendchild_model($node);

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
	"FROM #__locationfinder " .
	"HAVING distance < '%s' " .
	"ORDER BY distance LIMIT 0 , 20",
	$center_lat,
	$center_lng,
	$center_lat,
	$radius);

$db =& JFactory::getDBO();
$db->setQuery($query);
$rows = $db->loadAssocList();

// Iterate through the rows, adding XML nodes for each
foreach ($rows as $row){
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
  // Set the body to the description
  JResponse::setBody($row['description']);
  // Run the system plugins on the body
  $mainframe->triggerEvent('onAfterRender');
  // Use the body after the plugins have been run on it for the marker description
  $newnode->$dom_setattribute_model("desc",fixEncoding(JResponse::getBody()));
  $newnode->$dom_setattribute_model("lat", fixEncoding($row['lat']));
  $newnode->$dom_setattribute_model("lng", fixEncoding($row['lng']));
  $newnode->$dom_setattribute_model("distance", fixEncoding($row['distance']));
}

if( ( PHP_VERSION_ID < 50000 ) ) {
    $xmlfile = $dom->dump_mem();
    echo $xmlfile;
} else {
    echo $dom->saveXML($dom->documentElement);
}


// Fixes the encoding to uf8
function fixEncoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
} // fixEncoding 
?>
