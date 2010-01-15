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

$auth =& JFactory::getACL();
$auth->addACL('com_locationfinder', 'manage', 'users', 'super administrator');
$auth->addACL('com_locationfinder', 'manage', 'users', 'administrator');
$auth->addACL('com_locationfinder', 'manage', 'users', 'manager');

// Make sure the user is authorized to view this page
$user = & JFactory::getUser();
if (!$user->authorize( 'com_locationfinder', 'manage' )) {
        $mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

require_once (JPATH_COMPONENT.DS.'controller.php');

if($controller = JRequest::getVar('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

// Set the table directory
JTable::addIncludePath( JPATH_COMPONENT.DS.'tables' );

$classname = 'LocationsController'.ucfirst($controller);

$controller = new $classname( );
$controller->execute( JRequest::getVar('task'));
$controller->redirect();
