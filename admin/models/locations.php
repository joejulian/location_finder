<?
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

jimport( 'joomla.applicatoin.component.model' ); 

class LocationsModelLocations extends JModel
{
	var $_data;

	function _buildQuery() {
		$query = 'SELECT * FROM #__locationfinder';
		return $query;
	}

	function getData() {
		if ( empty( $this->_data )){
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}
		return $this->_data;
	}
}
