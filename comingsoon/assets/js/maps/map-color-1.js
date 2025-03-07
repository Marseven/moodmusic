// ------------------------------------------------
// Project Name: Axio Coming Soon Template
// Project Description: Axio - awesome coming soon template to kick-start your project
// Tags: mix_design, axio, coming soon, under construction, template, coming soon page, html5, css3
// Version: 4.0.0
// Build Date: April 2017
// Last Update: January 2023
// This product is available exclusively on Themeforest
// Author: mix_design
// Author URI: https://themeforest.net/user/mix_design
// File name: map-color-1.js
// ------------------------------------------------

// ------------------------------------------------
// Table of Contents
// ------------------------------------------------
//
//  1. Google Map Parameters
//  2. Google Map Custom Marker Icon
//  3. Style Of The Map
//  4. Google Map Options
//  5. Inizialize The Map
//  6. Custom Marker
//  7. Custom zoom-in/zoom-out Buttons
//
// ------------------------------------------------
// Table of Contents End
// ------------------------------------------------

$(function() {
  // Insert Your Google Maps Parameters
  var latitude = 40.761425,
    longitude = -73.977643,
    map_zoom = 14;

  // Google Map Custom Marker Icon
  var is_internetExplorer11= navigator.userAgent.toLowerCase().indexOf('trident') > -1;
  var marker_url = ( is_internetExplorer11 ) ? 'img/location/location-color-1.png' : 'img/location/location-color-1.svg';

    var main_color = '#140E32',
      saturation_value= 1,
      brightness_value= 5;

      // Style Of The Map
      var style= [
        {
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#b244b2"
            }
          ]
        },
        {
          "elementType": "labels.icon",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#d1add2"
            }
          ]
        },
        {
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "color": "#331533"
            }
          ]
        },
        {
          "featureType": "administrative.land_parcel",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "administrative.locality",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#fed1ff"
            }
          ]
        },
        {
          "featureType": "administrative.locality",
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "color": "#2d152e"
            }
          ]
        },
        {
          "featureType": "landscape",
          "stylers": [
            {
              "color": "#601560"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#cb52cb"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#dfaadf"
            }
          ]
        },
        {
          "featureType": "poi.park",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#eaa4ea"
            }
          ]
        },
        {
          "featureType": "poi.park",
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "color": "#321533"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#8c188c"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#b17cb1"
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#3f033f"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#add322"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#add322"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#83a30f"
            }
          ]
        },
        {
          "featureType": "road.highway.controlled_access",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#add322"
            }
          ]
        },
        {
          "featureType": "road.highway.controlled_access",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#add322"
            }
          ]
        },
        {
          "featureType": "road.highway.controlled_access",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#8eae19"
            }
          ]
        },
        {
          "featureType": "road.local",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#c78dc8"
            }
          ]
        },
        {
          "featureType": "transit",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#c189c2"
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#1e101e"
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#2a2d41"
            }
          ]
        }
      ];

      // Google Map Options
      var map_options = {
            center: new google.maps.LatLng(latitude, longitude),
            zoom: map_zoom,
            gestureHandling: 'cooperative',
            panControl: false,
            zoomControl: false,
            mapTypeControl: false,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            styles: style,
        }

      // Inizialize The Map
      var map = new google.maps.Map(document.getElementById('google-container'), map_options);

      // Custom Marker
      var marker = new google.maps.Marker({
          position: new google.maps.LatLng(latitude, longitude),
          map: map,
          visible: true,
        icon: marker_url,
      });

      // Custom zoom-in/zoom-out Buttons
      function CustomZoomControl(controlDiv, map) {

          var controlUIzoomIn= document.getElementById('zoom-in'),
            controlUIzoomOut= document.getElementById('zoom-out');
          controlDiv.appendChild(controlUIzoomIn);
          controlDiv.appendChild(controlUIzoomOut);

        google.maps.event.addDomListener(controlUIzoomIn, 'click', function() {
            map.setZoom(map.getZoom()+1)
        });
        google.maps.event.addDomListener(controlUIzoomOut, 'click', function() {
            map.setZoom(map.getZoom()-1)
        });
      }

      var zoomControlDiv = document.createElement('div');
      var zoomControl = new CustomZoomControl(zoomControlDiv, map);

        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(zoomControlDiv);

});
