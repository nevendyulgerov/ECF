

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define helper object
KenobiSoft.helper = KenobiSoft.helper || (function($) {


    // Scroll spy
    // Allows execution of callbacks before/after
    // given vertical offset, after 'onscroll' events
    // @param int offset
    // @param object callbacks
    var scrollSpy = function(offset, callbacks) {

        // validate callbacks
        var isValid =
            typeof callbacks.onBefore === 'function' &&
            typeof callbacks.onAfter === 'function';

        if ( ! isValid ) {
            console.warn('Invalid initialization for scrollSpy. Make sure to define valid callbacks.');
            return;
        }

        var docElem = document.documentElement;
        var didScroll = false;

        var scrollY = function() {
            return window.pageYOffset || docElem.scrollTop;
        };

        var scrollPage = function() {
            var sy = scrollY();

            if ( sy < offset ) {
                callbacks.onBefore();
            } else {
                callbacks.onAfter();
            }

            didScroll = false;
        };

        var init = function() {
            window.addEventListener('scroll', function(event) {
                if ( !didScroll ) {
                    setTimeout( scrollPage, 50 );
                    didScroll = true;
                }
            }, false);
        };

        init();
    };


    // Buffered event
    // Allows execution of callback after given delay
    var bufferedEvent = (function () {
        var timers = {};

        return function (callback, ms, uniqueId) {
            if ( ! uniqueId ) {
                uniqueId = '0';
            }

            if ( timers[uniqueId] ) {
                clearTimeout(timers[uniqueId]);
            }

            timers[uniqueId] = setTimeout(callback, ms);
        };
    })();


    // return public API
    return {
        scrollSpy: scrollSpy,
        bufferedEvent: bufferedEvent
    }

})( jQuery );