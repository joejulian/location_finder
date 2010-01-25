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

defined('_JEXEC') or die('Restricted Access'); // do not run without Joomla

$document = & JFactory :: getDocument();
$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
$document->addScript( JURI::root(true) . '/components/com_locationfinder/views/location/tmpl/gmap-v3.js');

JHTML :: _('behavior.mootools');

$js = "
	
	function addEvent(obj, evType, fn){ 
	if (obj.addEventListener){ 
	obj.addEventListener(evType, fn, false); 
	return true; 
	} else if (obj.attachEvent){ 
	var r = obj.attachEvent('on'+evType, fn); 
	return r; 
	} else { 
	return false; 
	} 
	}
	
	
	addEvent(window, 'load', GMapInit);
	
	";

$document->addScriptDeclaration($js);
?>
<label for="addressInput">Address:</label> <input type="text" id="addressInput" size="30" onkeypress="return searchLocationsOnEnter(this,event)"/>
<label for="radiusSelect">Radius:</label> <select id="radiusSelect" onkeypress="return searchLocationsOnEnter(this,event)">
    <option value="5">5</option>
    <option value="25" selected>25</option>
    <option value="100">100</option>
    <option value="200">200</option>
</select>

<input type="button" onclick="searchLocations()" value="Search" />
<br/><br/>
<table width="100%">
    <tr>
	<td width="20%">
	    <div id="GMsidebar" style="overflow: auto; height: 400px; font-size: 11px; color: #000"></div>
	</td>
	<td width="80%">
	    <div id="GMap" style="overflow:hidden; width:100%; height:450px;"></div>
	</td>
    </tr>
</table>
