

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define editor metafield
KenobiSoft.metafields.editor = KenobiSoft.metafields.editor || function($component) {

    // define local vars
    var $editor        = $component.find('.wysiwyg-wrapper'),
        $editorStorage = $component.find('textarea'),
        iconsDir       = $component.data('icons-dir');


    var init = function() {

        // initialize the editor
        $editor.trumbowyg({
            svgPath: iconsDir
        });

        // load editor content
        $editor.trumbowyg('html', $editorStorage.val());

        // update field value on multiple events
        $editor.on('keyup keydown keypress click', function () {
            var editorVal = $editor.html();

            var encodedVal = editorVal.replace(/\//g, "'");
            $editorStorage.val(encodedVal);
        });

        // update field value on button press events
        $editor.parent().find('button').on('click', function () {
            var editorVal = $editor.html();

            var encodedVal = editorVal.replace(/\//g, "'");
            $editorStorage.val(encodedVal);
        });
    };

    init();
};