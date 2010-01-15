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

function findLocation(form) {
    var geocoder = new google.maps.Geocoder();
    var address = form.street.value + ' ' 
                + form.city.value + ' '
                + form.state.value + ' ' 
                + form.postal_code.value + ' '
                + form.country.value;
    geocoder.geocode( { 'address' : address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            fillFormFields(form, results[0].geometry.location);
        } else {
            alert(status);
        }
    });
}

function fillFormFields(myform, response) {
    myform.lat.value = response.lat();
    myform.lng.value = response.lng();
}

