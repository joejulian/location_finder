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

jimport ('joomla.application.component.view');

class LocationsViewLocation extends JView {
	function display($tpl = null) {
		$location =& $this->get('Data');
		$isNew = $location->id < 1;
                if($isNew) {
			$location->description = '<img style="margin: 6px;float: left;" alt="spa pic goes here" src="components/com_locationfinder/images/locationfinder/placeholder.jpg" height="200" /><p>Lorem ipsum dolor sit amet, dui risus, nec odio. Luctus nunc, cras habitant, magna malesuada. Quis nisl felis, dui nec, porttitor a commodo. Metus in gravida, elementum justo molestie, porta a. In cum.</p>
<p>A etiam. Mollis commodo sed, ipsum risus ultricies. Ipsum in, adipiscing ullamcorper quis, tortor nullam. Dignissim luctus, suscipit erat commodo, nulla proin morbi. Donec turpis.</p>
<p>Eleifend quis, nec vulputate, id elementum. Libero amet, sem pellentesque placerat. Ultricies ultricies, sem massa, repellendus leo varius. Amet dolor vulputate, wisi elit, gravida nulla. Leo felis in, pede dictum, nullam justo. Sollicitudin magna, odio lobortis.</p><br/>
<h2>Hours</h2>
<table border="0">
<tbody>
<tr>
<td>Monday - Friday</td>
<td>X am - X pm</td>
</tr>
<tr>
<td>Saturday</td>
<td>X am - X pm</td>
</tr>
<tr>
<td>Sunday</td>
<td>X am - X pm</td>
</tr>
</tbody>
</table>';
		}

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Location').': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('location', $location);

		parent::display($tpl);
	}
}
