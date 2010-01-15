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

class LocationsControllerLocation extends JController {
	function __construct() {
		parent::__construct();

		$this->registerTask ( 'add', 'edit' );
	}

	function display() {
		parent::display();
	}

	function edit() {
		JRequest::setVar( 'view', 'location' );
		JRequest::setVar( 'layout', 'form' );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}

	function save() {
		$model = $this->getModel('location');

		if ($model->store($post)) {
			$msg = JText::_( 'Location Saved' );
		} else {
			$msg = JText::_( 'Error Saving Location' );
		}

		$link = 'index.php?option=com_locationfinder'; // what's the joomla way to do this?
		$this->setRedirect($link, $msg);
	}

	function remove() {
		$model = $this->getModel('location');

		if ($model->delete()) {
			$msg = JText::_( 'Location(s) Deleted' );
		} else {
			$msg = JText::_( 'Error: One Or More Locations Could Not Be Deleted' );
		}

		$link = 'index.php?option=com_locationfinder';
		$this->setRedirect($link, $msg);
	}

	function cancel() {
		$msg = JText::_( 'Operation Cancelled' );
	
		$link = 'index.php?option=com_locationfinder';
		$this->setRedirect($link, $msg);
	}
		
}
