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
// File name: map-color-2.js
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
  var marker_url = ( is_internetExplorer11 ) ? 'img/location/location-color-2.png' : 'img/location/location-color-2.svg';

    var main_color = '#140E32',
      saturation_value= 1,
      brightness_value= 5;

      // Style Of The Map
      var style= [
        {
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#00346b"
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
              "color": "#94bbe6"
            }
          ]
        },
        {
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "color": "#001429"
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
              "color": "#b4d0ee"
            }
          ]
        },
        {
          "featureType": "administrative.locality",
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "color": "#00162e"
            }
          ]
        },
        {
          "featureType": "landscape",
          "stylers": [
            {
              "color": "#00254d"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#00458a"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#a9c6e5"
            }
          ]
        },
        {
          "featureType": "poi.park",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#80a7d0"
            }
          ]
        },
        {
          "featureType": "poi.park",
          "elementType": "labels.text.stroke",
          "stylers": [
            {
              "color": "#001b38"
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#002142"
            },
            {
              "weight": 1
            }
          ]
        },
        {
          "featureType": "road",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#6595c8"
            }
          ]
        },
        {
          "featureType": "road.arterial",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#002042"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#fee715"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#e1cd14"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#c1b310"
            }
          ]
        },
        {
          "featureType": "road.highway.controlled_access",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#fee715"
            }
          ]
        },
        {
          "featureType": "road.highway.controlled_access",
          "elementType": "geometry.fill",
          "stylers": [
            {
              "color": "#fee715"
            }
          ]
        },
        {
          "featureType": "road.highway.controlled_access",
          "elementType": "geometry.stroke",
          "stylers": [
            {
              "color": "#d7c81d"
            }
          ]
        },
        {
          "featureType": "road.local",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#759dc7"
            }
          ]
        },
        {
          "featureType": "transit",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#7d98b5"
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#071c36"
            }
          ]
        },
        {
          "featureType": "water",
          "elementType": "labels.text.fill",
          "stylers": [
            {
              "color": "#7b8fa3"
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
