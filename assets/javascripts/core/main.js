
// Initialize framework
jQuery(document).ready(function($) {

    // define core object
    var core = (function() {
        var coreLoaded =
            KenobiSoft &&
            KenobiSoft.manager &&
            KenobiSoft.helper &&
            KenobiSoft.metafields &&
            KenobiSoft.widgets;

        var init = function() {
            if ( coreLoaded ) {

                // initialize metafields
                KenobiSoft.manager.initMetafields({
                    components: KenobiSoft.metafields,
                    selector: '.custom-metafield',
                    dataSelector: 'metafield'
                });

                // initialize widgets
                KenobiSoft.manager.initWidgets({
                    components: KenobiSoft.widgets,
                    selector: '.custom-widget',
                    dataSelector: 'widget'
                });
            }
        };

        return {
            init: init
        };
    })();

    // init core
    core.init();
});