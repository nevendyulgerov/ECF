

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define gallery metafield
KenobiSoft.metafields.gallery = KenobiSoft.metafields.gallery || function($component) {

    // define local vars
    var $ = jQuery,
        meta_image_frame,
        galleries       = KenobiSoft.options.galleries,
        $galleryWrapper = $component.find('.gallery-wrapper'),
        $valStorage     = $component.find('textarea'),
        $btnAddImg      = $component.find('.button-add'),
        $galleryImg     = $component.find('.gallery-image-wrapper'),
        sly             = null;

    var init = function() {

        // initialize image magnification
        var enableMagnification = function() {
            $galleryImg = $component.find('.gallery-image-wrapper');

            $galleryImg.magnificPopup({
                delegate: '.gallery-image-link',
                tLoading: 'Loading image #%curr%...',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        };

        // enable sliders
        var enableSliders = function() {
            var $sliders = $('.gallery-frame');

            for (var i = 0, j = $sliders.length; i < j; i++) {
                var id = $sliders.eq(i).attr('id');

                // initialize slider
                initSlider(id);
            }
        };

        var initSlider = function(id) {

            // Call Sly on frame
            sly = new Sly('#' + id, {
                horizontal: 1,
                itemNav: 'basic',
                smart: 1,
                activateOn: 'click',
                mouseDragging: 1,
                touchDragging: 1,
                releaseSwing: 1,
                startAt: 0,
                scrollBy: 0,
                speed: 300,
                elasticBounds: 1,
                dragHandle: 1,
                dynamicHandle: 1,
                clickBar: 1
            }, function() {}).init();

            galleries.push({
                slider: sly,
                id: id
            });

            increaseSliderWidth();
        };

        var reloadSliders = function() {
            var $sliders = $('.gallery-frame');

            for (var i = 0, l = $sliders.length; i < l; i++) {
                galleries[i].slider.destroy();
                $sliders.eq(i).attr('id', galleries[i].id);
                initSlider(galleries[i].id);
            }
        };

        // increase slider width to fit all images
        var increaseSliderWidth = function() {

            // Get sliders
            var $sliders = $('.gallery-frame');

            // Set offset for the slider width
            var offset = 200;

            for (var i = 0, j = $sliders.length; i < j; i++) {

                // Get slider
                var $slider = $sliders.eq(i).find('ul');

                // Get slider width
                var sliderWidth = $slider.width();

                // Update slider width
                sliderWidth += offset;

                // Update slider
                $slider.css({width: sliderWidth + 'px'});
            }
        };

        // enable slider after 100 milliseconds
        setTimeout(function() {
            try {
                enableSliders();
            } catch(e) {
                reloadSliders();
            }
        }, 100);

        // enable magnification
        enableMagnification();
    };

    // Init gallery
    init();

    var contains = function(ids, val) {

        for (var i in ids.urls) {
            var cleanUrl = val.replace(/-\d+x\d+((\.png)|(\.jpg)|(\.gif)|(\.tif))/g, '');

            if (ids.urls[i].url.indexOf(cleanUrl) !== -1 && !ids.checked[i] ) {
                ids.checked[i] = true;
                return ids.urls[i];
            }
        }

        return -1;
    };

    // Sets up the media library frame
    meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
        title: 'Choose or Upload an Image',
        button: {
            text:  'Use this image'
        },
        multiple: true
    });

    // Runs when an image is added
    meta_image_frame.on('select', function(){

        // Get image data
        var data = meta_image_frame.state().get('selection').toJSON();

        var urls = [];

        for (i in data) {
            urls.push({
                id: data[i].id,
                url: data[i].url
            });
        }

        var dataStr = JSON.stringify(urls);

        // Add urls as value to the metafield
        $valStorage.html(dataStr);

        $galleryWrapper.hide();

        $galleryWrapper.empty();

        var $html = '<div class="gallery-frame"><ul>';

        for (var u in urls) {

            $html += '<li><div class="gallery-image-wrapper"><a class="gallery-image-link" href="' + urls[u].url + '"><img src="' + urls[u].url + '"/></a></div></li>';
        }

        $html += '</ul></div>';

        $galleryWrapper.append($html);

        // initialize the new gallery
        init();

        $galleryWrapper.fadeIn(300);
    });

    // Runs on open
    meta_image_frame.on('open', function() {

        setTimeout(function() {
            var selection = meta_image_frame.state().get('selection');

            var $allImages = $('.attachments li');

            var ids = {
                urls: JSON.parse($valStorage.val()),
                checked: []
            };

            for (var k in ids.urls) {
                ids.checked.push(false);
            }

            for (var i = 0, j = $allImages.length; i < j; i++) {
                var $img = $allImages.eq(i);
                var id = $img.find('img').attr('src');

                if ( contains(ids, id) !== -1 && !$img.hasClass('selected') ) {
                    var idOriginal = contains(ids, id);

                    $img.addClass('selected');
                    selection.add(wp.media.attachment(idOriginal));
                }
            }
        }, 500);
    });

    $btnAddImg.click(function(e){
        e.preventDefault();

        // Opens the media library frame.
        meta_image_frame.open();
    });
};