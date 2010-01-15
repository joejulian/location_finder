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
?>

<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
            <th width="2%">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="35%">
                <?php echo JText::_( 'Name' ); ?>
            </th>
            <th width="20%">
                <?php echo JText::_( 'City' ); ?>
            </th>
            <th width="5%">
                <?php echo JText::_( 'State' ); ?>
            </th>
            <th width="15%">
                <?php echo JText::_( 'Country' ); ?>
            </th>
            <th width="30%">
                <?php echo JText::_( 'URL' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];
	$checked = JHTML::_( 'grid.id', $i, $row->id );
	$link = JRoute::_( 'index.php?option=com_locationfinder&controller=location&task=edit&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $row->id; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
            </td>
            <td>
                <?php echo $row->city; ?>
            </td>
            <td>
                <?php echo $row->state; ?>
            </td>
            <td>
                <?php echo $row->country; ?>
            </td>
            <td>
                <?php echo $row->url; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
</div>
 
<input type="hidden" name="option" value="com_locationfinder" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="location" />
 
</form>

