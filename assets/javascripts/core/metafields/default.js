

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define default metafield
KenobiSoft.metafields.default = KenobiSoft.metafields.default || function($component) {

    // define local vars
    var $ = jQuery,
        $pluginView  = $('.plugin-view'),
        $navElements = $pluginView.find('.nav-element'),
        $btnSave     = $pluginView.find('.button-save'),
        $hiddenSave  = $pluginView.find('.button-submit-hidden');

    // save event: submit plugin form
    $btnSave.on('click', function(e) {
        e.preventDefault();

        // submit plugin form
        $hiddenSave.click();
    });

    // redirect event: redirect to page on navigation click
    $navElements.on('click', function(e) {
        window.location.href = $(this).find('a').attr('href');
    });

    // highlight wysiwyg on click
    $('.custom-metafield[data-metafield="editor"]').on('click', function() {
        var $box = $(this).find('.trumbowyg-box');

        if ( ! $box.hasClass('active') ) {
            $box.addClass('active');
            $('.trumbowyg-box').not($box).removeClass('active');
        }
    });
};