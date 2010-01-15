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

class TableLocation extends JTable
{
	var $id = null;
	var $name = null;
	var $street = null;
	var $city = null;
	var $state = null;
	var $postal_code = null;
	var $country = null;
	var $phone = null;
	var $email = null;
	var $url = null;
	var $lat = 0;
	var $lng = 0;
	var $image = null;
	var $thumbnail = null;
	var $description = null;
	var $published = 0;
	var $checked_out = 0;

	function __construct(&$db)
	{
		parent::__construct( '#__locationfinder', 'id', $db );
	}
}
?>
