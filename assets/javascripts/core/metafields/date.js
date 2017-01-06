

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define date metafield
KenobiSoft.metafields.date = KenobiSoft.metafields.date || function($component) {

    // define local vars
    var $date      = $component.find('input'),
        dateFormat = $component.data('format');

    // define init function
    var init = function() {

        $date.datepicker({
            dateFormat: dateFormat
        });
    };

    init();
};