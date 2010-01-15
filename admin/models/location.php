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

jimport( 'joomla.applicatoin.component.model' );

class LocationsModelLocation extends JModel
{
	function __construct() {
		parent::__construct();

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setID($id) {
		$this->_id = $id;
		$this->_data = null;
	}

	function &getData() {
		if(empty ($this->_data )) {
			$query = 'SELECT * FROM #__locationfinder WHERE id = ' . $this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();

		}

		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = null;
			$this->_data->street = null;
			$this->_data->city = null;
			$this->_data->state = null;
			$this->_data->postal_code = null;
			$this->_data->country = null;
			$this->_data->phone = null;
			$this->_data->url = null;
			$this->_data->email = null;
			$this->_data->lat = 0;
			$this->_data->lng = 0;
			$this->_data->image = null;
			$this->_data->thumbnail = null;
			$this->_data->description = null;
		}

		return $this->_data;
	}

	// need to do some basic error checking
	function store() {
		$row =& $this->getTable();
		$data = JRequest::get( 'post' );
		$data['description'] = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if(!$row->bind($data) or !$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if(!$row->store()) {
			$this->setError( $row->getErrorMsg());
			return false;
		}

		return true;
	}

	function delete() {
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
}
