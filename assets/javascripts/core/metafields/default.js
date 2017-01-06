

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.metafields = KenobiSoft.metafields || {};


// Define default metafield
KenobiSoft.metafields.default = KenobiSoft.metafields.default || function($component) {

    // define local vars
    var $ = jQuery;

    // save event: submit plugin form
    $('.button-save').on('click', function(e) {
        e.preventDefault();

        // submit plugin form
        $('.button-submit-hidden').click();
    });


    // redirect event: redirect to page on navigation click
    $('.nav-element').on('click', function(e) {
        window.location.href = $(this).find('a').attr('href');
    });

    /*
    // check checkbox on label click
    $('label').on('click', function(e) {
        $(this).find('input[type="checkbox"]').trigger('click');
    });
    */

    // highlight wysiwyg on click
    $('.ksfc-metafield[data-metafield="wysiwyg"]').on('click', function() {
        var $box = $(this).find('.trumbowyg-box');

        if ( ! $box.hasClass('active') ) {
            $box.addClass('active');

            $('.trumbowyg-box').not($box).removeClass('active');
        }
    });


    // remove wysiwyg highlight
    $('.ksfc-metafield').on('click', function(e) {
        var $metafield = $(this);

        if ( $metafield.attr('data-metafield') !== 'wysiwyg' ) {
            $('.trumbowyg-box').length > 0 && $('.trumbowyg-box').removeClass('active');
        }
    });
};