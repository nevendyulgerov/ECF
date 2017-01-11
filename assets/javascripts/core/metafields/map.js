

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define map metafield
KenobiSoft.metafields.map = KenobiSoft.metafields.map || function($component) {

    // define local vars
    var mapId       = $component.data('map-id'),
        mapDiv      = document.getElementById(mapId),
        markerDir   = $component.data('marker-dir'),
        $controls   = $component.find('.map-controls'),
        $btnCtrl    = $component.find('.button-open-map-settings'),
        $btnUpdate  = $component.find('.button-update-map'),
        $valWrapper = $component.find('textarea'),
        $latCtrl    = $controls.find('input.lat'),
        $lngCtrl    = $controls.find('input.lng'),
        $zoomCtrl   = $controls.find('input.zoom'),
        lat         = $component.data('lat'),
        lng         = $component.data('lng'),
        zoom        = $component.data('zoom'),
        mapStyle    = [];


    // define init function
    var init = function() {

        console.log(mapDiv);

        var map = new google.maps.Map(mapDiv, {
            center: {
                lat: lat,
                lng: lng
            },
            zoom: zoom,
            styles: mapStyle,
            scrollwheel: false
        });

        // initialize marker
        var marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng:lng
            },
            map: map,
            title: 'Selected location',
            icon: markerDir
        });

        // enable responsive support for map
        google.maps.event.addDomListener(window, "resize", function() {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);
        });


        // save map data
        $controls.find('input').on('keyup keydown keypress click', function(e) {

            var data = {
                lat: parseFloat($latCtrl.val()),
                lng: parseFloat($lngCtrl.val()),
                zoom: parseFloat($zoomCtrl.val())
            };

            $valWrapper.html(JSON.stringify(data));
        });

        // toggle map controls
        $btnCtrl.on('click', function(e) {
            e.preventDefault();

            var isOpened = $btnCtrl.attr('data-control-opened') !== 'false';
            $controls.addClass('active');

            if ( isOpened ) {
                $controls.removeClass('active');
                $btnCtrl.attr('data-control-opened', false);

            } else {
                $controls.addClass('active');
                $btnCtrl.attr('data-control-opened', true);
            }
        });

        // update map
        $btnUpdate.on('click', function(e) {
            e.preventDefault();

            var data = {
                lat: parseFloat($latCtrl.val()),
                lng: parseFloat($lngCtrl.val()),
                zoom: parseInt($zoomCtrl.val())
            };

            var map = new google.maps.Map(mapDiv, {
                center: {
                    lat: data.lat,
                    lng: data.lng
                },
                zoom: data.zoom,
                styles: mapStyle
            });

            var marker = new google.maps.Marker({
                position: {
                    lat: data.lat,
                    lng: data.lng
                },
                map: map,
                title: 'Selected location',
                icon: markerDir
            });
        });
    };

    init();
};







