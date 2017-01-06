

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define metafields object
KenobiSoft.manager = KenobiSoft.manager || (function($) {


    // Initialize components
    // @param object args
    var initComponents = function(args) {
        var isValid =
            typeof args              === 'object' &&
            typeof args.components   === 'object' &&
            typeof args.selector     === 'string' &&
            typeof args.dataSelector === 'string';

        if ( ! isValid ) {
            console.warn('Invalid initialization for initComponents. Make sure to provide a valid args object, containing components, selector and dataSelector');
            return false;
        }

        var components   = args.components;
        var selector     = args.selector;
        var dataSelector = args.dataSelector;

        // get components from DOM
        var $components = $(selector);

        // iterate all found DOM components
        for ( var i = 0, j = $components.length; i < j; i++ ) {

            // get DOM component
            var $component = $components.eq(i);

            // get component name
            var component  = $component.data(dataSelector);

            // validate component
            var isValidComponent = components.hasOwnProperty(component) && typeof components[component] === 'function';

            // check if component is valid
            if ( isValidComponent ) {

                // call component
                components[component]($component);
            }
        }

        // check if default component exists
        var componentDefaultExists =
            components.hasOwnProperty('default') &&
            typeof components.default === 'function';

        // if default component exists
        if ( componentDefaultExists ) {
            // call default
            components.default();
        }
    };


    var initMetafields = function(args) {
        initComponents(args);
    };


    var initWidgets = function(args) {
        initComponents(args);
    };


    // return public API
    return {
        initMetafields: initMetafields,
        initWidgets: initWidgets
    }

})( jQuery );