

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define checkbox metafield
KenobiSoft.metafields.checkbox = KenobiSoft.metafields.checkbox || function($component) {

    // define local vars
    var $checkbox    = $component.find('input[type="checkbox"]'),
        $hiddenInput = $component.find('input[type="hidden"]'),
        checkedVal   = 1,
        uncheckedVal = 0;

    // define init function
    var init = function() {
        $checkbox.on('change', function() {
            if ( $checkbox.is(':checked') ) {
                $hiddenInput.val(checkedVal);
                $checkbox.attr('checked', true);
            } else {
                $hiddenInput.val(uncheckedVal);
                $checkbox.attr('checked', false);
            }
        });

        $component.find('.description').on('click', function(e) {
            if ( $checkbox.is(':checked') ) {
                $hiddenInput.val(uncheckedVal);
                $checkbox.attr('checked', false);
            } else {
                $hiddenInput.val(checkedVal);
                $checkbox.attr('checked', true);
            }
        });
    };

    init();
};