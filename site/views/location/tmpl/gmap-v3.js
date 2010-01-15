//<![CDATA[
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

google.maps.Map.prototype.markers = new Array();
google.maps.Map.prototype.getMarkers = function() {
	return this.markers
};
google.maps.Map.prototype.clearMarkers = function() {
	for ( var i = 0; i < this.markers.length; i++) {
		this.markers[i].setMap(null);
	}
	this.markers = new Array();
};
google.maps.Marker.prototype._setMap = google.maps.Marker.prototype.setMap;
google.maps.Marker.prototype.setMap = function(map) {
	if (map) {
		map.markers[map.markers.length] = this;
	}
	this._setMap(map);
}

var map;
var geocoder;
var lastinfo;

function GMapInit() {
	var latlng = new google.maps.LatLng(47.570620, -122.323836);
	geocoder = new google.maps.Geocoder();
	var myOptions = {
		zoom : 9,
		center : latlng,
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		navigationControl : true,
		mapTypeControl : true,
		scaleControl : true
	}

	map = new google.maps.Map(document.getElementById('GMap'), myOptions);
	document.getElementById('addressInput').focus()
	document.getElementById('addressInput').select()
	/*
	 * map.addControl(new GLargeMapControl()); map.addControl(new
	 * GMapTypeControl());
	 */
}

function searchLocationsOnEnter(myfield, e) {
	var keycode;
	if (window.event)
		keycode = window.event.keyCode;
	else if (e)
		keycode = e.which;
	else
		return true;

	if (keycode == 13) {
		searchLocations();
		return false;
	} else {
		return true;
	}
}

function searchLocations() {
	var address = document.getElementById('addressInput').value;
	geocoder.geocode( {
		'address' : address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			searchLocationsNear(address, results[0].geometry.location);
		} else {
			alert(status);
		}
	});
}

function searchLocationsNear(saddr, center) {
	var radius = document.getElementById('radiusSelect').value;
	var searchUrl = 'components/com_locationfinder/phpsqlsearch_genxml.php?lat='
			+ center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
	map.clearMarkers();
	LoadXML(searchUrl, 'markers', function(element, xml) {
		var markers = xml[0].getElementsByTagName('marker');

		var sidebar = document.getElementById('GMsidebar');
		sidebar.innerHTML = '';
		if (markers.length == 0) {
			map.setCenter(center, 9);
			sidebar.innerHTML = 'No results found.';
			return;
		}

		var bounds = new google.maps.LatLngBounds();
		for ( var i = 0; i < markers.length; i++) {
			var point = new google.maps.LatLng(parseFloat(markers[i]
					.getAttribute('lat')), parseFloat(markers[i]
					.getAttribute('lng')));

			var marker = createMarker(saddr, point, markers[i]);
			var sidebarEntry = createSidebarEntry(marker, markers[i]);
			sidebar.appendChild(sidebarEntry);
			bounds.extend(point);
		}
		map.fitBounds(bounds);

		document.getElementById('addressInput').select();
		document.getElementById('addressInput').focus();
	});
}

function createMarker(saddr, point, xml) {
	var name = xml.getAttribute('name');
	var addr1 = xml.getAttribute('addr1');
	var addr2 = xml.getAttribute('addr2');
	var country = xml.getAttribute('country')
	var phone = xml.getAttribute('phone');
	var url = xml.getAttribute('url');
	var email = xml.getAttribute('email');
	var desc = xml.getAttribute('desc');
	var address = xml.getAttribute('address');
	var html = '<div style="float:left; margin-right: 10px;">'
            + ( url ? '<a href="' + url + '" target="_blank">' : '')
            + '<b>' + name + '</b>' 
            + ( url ? '</a>' : '' )
            + addline(addr1)
            + addline(addr2) 
            + addline(country)
            + ( phone ? '<br/><a href="tel:' + phone + '">' + phone + '</a>' : '')
            + ( email ? '<br/><a href="mailto:' + email + '">' + email + '</a>' : '')
            + '<br/><br/><a href="http://maps.google.com/maps?saddr=' + escape(saddr) 
            + '&amp;daddr=' + escape(address) + '" target="_blank">'
            + '<span style="color:green">Get Driving Directions</span></a>'
            + '</div>'
            + ( desc ? '<div style="margin: 00  6px;">' + desc + '</div>' : '');
	var marker = new google.maps.Marker( {
		position : point,
		map : map,
		title : name,
		clickable : true
	});
	var info = new google.maps.InfoWindow( {
		content : html
	});
	google.maps.event.addListener(marker, 'click', function() {
		if (lastinfo) {
			lastinfo.close();
		}
		info.open(map, marker);
		lastinfo = info;
	});
	return marker;
}

function createSidebarEntry(marker, xml) {
	var name = xml.getAttribute('name');
	var addr1 = xml.getAttribute('addr1');
	var addr2 = xml.getAttribute('addr2');
	var country = xml.getAttribute('country')
	var phone = xml.getAttribute('phone');
	var url = xml.getAttribute('url');
	var email = xml.getAttribute('email');
	var desc = xml.getAttribute('desc');
	var distance = parseFloat(xml.getAttribute('distance'));
	var address = xml.getAttribute('address');

	var div = document.createElement('div');
	var html = distance.toFixed(1) + ' mile' + (distance >= 2 ? 's' : '')
                 + '<br/><b>' + name + '</b>' 
                 + addline(addr1)
                 + addline(addr2) 
                 + addline(country) 
                 + ( phone ? '<br/><a href="tel:' + phone + '">' + phone + '</a>' : '')
                 + ( email ? '<br/><a href="mailto:' + email + '">' + email + '</a>' : '')
                 + ( url ? '<br/><a href="' + url + '" target="_blank">' + url + '</a>' : '');
	div.innerHTML = html;
	div.style.cursor = 'pointer';
	div.style.marginBottom = '5px';
	google.maps.event.addDomListener(div, 'click', function() {
		google.maps.event.trigger(marker, 'click');
	});
	google.maps.event.addDomListener(div, 'mouseover', function() {
		div.style.backgroundColor = '#eee';
	});
	google.maps.event.addDomListener(div, 'mouseout', function() {
		div.style.backgroundColor = '#fff';
	});
	return div;
}

function LoadXML(url, element, runfunc) {
	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest) {
		var req = new XMLHttpRequest();
		req.onreadystatechange = processReqChange;
		req.open("GET", url, true);
		req.send(null);
		// branch for IE/Windows ActiveX version
	} else if (window.ActiveXObject) {
		var req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.onreadystatechange = processReqChange;
			req.open("GET", url, true);
			req.send();
		}
	}
	// handle onreadystatechange event of req object

	function processReqChange() {
		// only if req shows "loaded"
		if (req.readyState == 4) {
			// only if "OK"
			if (req.status == 200) {
				runfunc(element, req.responseXML.getElementsByTagName(element));
			} else {
				alert("There was a problem retrieving the XML data:\n"
						+ req.statusText);
			}
		}
	}
}

function addline(st) {
    return ( st ? '<br/>' + st : '');
}

// ]]>

