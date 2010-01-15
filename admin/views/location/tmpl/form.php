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

$document = &JFactory::getDocument();
$editor   = &JFactory::getEditor();

$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
$document->addScript( '/administrator/components/com_locationfinder/views/location/tmpl/form-v3.js');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="60" value="<?php echo $this->location->name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="street">
					<?php echo JText::_( 'Street' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="street" id="street" size="32" maxlength="80" value="<?php echo $this->location->street;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="city">
					<?php echo JText::_( 'City' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="city" id="city" size="30" maxlength="30" value="<?php echo $this->location->city;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="state">
					<?php echo JText::_( 'State/Province' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="state" id="state" size="2" maxlength="2" value="<?php echo $this->location->state;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="postal_code">
					<?php echo JText::_( 'Zip/Postal Code' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="postal_code" id="postal_code" size="10" maxlength="20" value="<?php echo $this->location->postal_code;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="country">
					<?php echo JText::_( 'Country' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country" id="country" size="30" maxlength="30" value="<?php echo $this->location->country;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="phone">
					<?php echo JText::_( 'Phone' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="phone" id="phone" size="32" maxlength="250" value="<?php echo $this->location->phone;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="url">
					<?php echo JText::_( 'URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="url" id="url" size="32" maxlength="250" value="<?php echo $this->location->url;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
					<?php echo JText::_( 'E-Mail' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo $this->location->email;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="lat">
					<?php echo JText::_( 'Latitude' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="lat" id="lat" size="10" maxlength="10" value="<?php echo $this->location->lat;?>" />
				<input class="button" type="button" name="lookup" Value="Lookup" onClick="findLocation(this.form)" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="lng">
					<?php echo JText::_( 'Longitude' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="lng" id="lng" size="10" maxlength="10" value="<?php echo $this->location->lng;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $editor->display('description',$this->location->description, '550', '300', '60', '20', array('pagebreak','readmore'));?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_locationfinder" />
<input type="hidden" name="id" value="<?php echo $this->location->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="location" />
</form>
