

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define image metafield
KenobiSoft.metafields.image = KenobiSoft.metafields.image || function($component) {

    // define local vars
    var $ = jQuery,
        meta_image_frame,
        $buttonOpen   = $component.find('.button-open-image'),
        $buttonAdd    = $component.find('.button-add'),
        $buttonRemove = $component.find('.button-remove'),
        $metafield    = $component.find('textarea'),
        $metafieldImg = $component.find('img'),
        selectedImgId = null,
        settings      = ECF_Settings;

    var init = function() {
        $buttonAdd.on('click', function(e) {
            e.preventDefault();

            if ( meta_image_frame ) {
                meta_image_frame.open();
                return;
            }

            // Sets up the media library frame
            meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                title: 'Select or upload an image',
                button: {
                    text: 'Use this image'
                }
            });

            // Runs when an image is selected.
            meta_image_frame.on('select', function(){
                // get media file and create json representation of the data
                var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

                var imageUrl     = media_attachment.url;
                var relativePath = imageUrl.replace(settings.site_url, '');

                // store meaningful data
                var imageObj = {
                    id: media_attachment.id,
                    url: relativePath
                };

                selectedImgId = imageObj.id;

                // store data in metafield
                $metafield.val(JSON.stringify(imageObj));

                // toggle buttons
                $buttonRemove.removeClass('hidden');
                $buttonOpen.attr('href', imageObj.url);
                $metafieldImg.attr('src', imageObj.url);
                $buttonOpen.removeClass('hidden');
                $metafieldImg.removeClass('hidden');
            });

            // Opens the media library frame.
            meta_image_frame.open();
        });

        $buttonRemove.on('click', function(e) {
            e.preventDefault();

            // clear data for metafield
            $metafield.val('');

            // toggle buttons
            $buttonOpen.addClass('hidden');
            $buttonRemove.addClass('hidden');
        });

        // initialize image magnification
        $buttonOpen.magnificPopup({
            type: 'image'
        });
    };

    init();
};