(function($) {

    $.fn.notifier = (function() {

        // @private property object icons
        var icons = {
            success: 'dashicons dashicons-yes',
            info:    'dashicons dashicons-info',
            warning: 'dashicons dashicons-warning',
            failure: 'dashicons dashicons-no'
        };

        // @private method hide
        var show  = function() {};

        // @private method hide
        var hide  = function() {};

        // @private property number hideAfter
        var hideAfter = 7500;

        // @private object notification
        var notification = (function() {
            return {
                styles: {
                    wrapper: 'position:fixed; top: 40px; right: 20px; line-height: 1.2; z-index: 999;',
                    notification: 'opacity: 0.0, display: none; position: relative; width: 320px; padding: 25px; margin-bottom: 20px; z-index: 999; box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12); transition: all, 0.3s;',
                    title: 'font-size: 30px; text-transform: uppercase;',
                    subtitle: '',
                    icon: 'position: relative; font-size: 48px; padding: 0px 10px 0px 0px;',
                    success: 'background-color: #dff0d8; color: #3c763d;',
                    info: 'background-color: #d9edf7; color: #31708f;',
                    warning: 'background-color: #fcf8e3; color: #8a6d3b;',
                    failure: 'background-color: #f2dede; color: #a94442;'
                },
                globalWrapper: 'body',
                wrapper: 'notifications',
                selector: 'notification',
                init: function() {
                    var $globalWrapper = $(this.globalWrapper);

                    $globalWrapper.css({ position: 'relative' });
                    $globalWrapper.append('<div class="' + this.wrapper + '" style="' + this.styles.wrapper + '"></div>');
                },
                exists: function() {
                    return $('.' + this.wrapper).length > 0;
                },
                getCallbacks: function(callbacks) {
                    callbacks      = typeof callbacks === 'object' ? callbacks : {};
                    callbacks.show = this.getCallback(callbacks['show']);
                    callbacks.hide = this.getCallback(callbacks['hide']);

                    return callbacks;
                },
                getCallback: function(callback) {
                    return typeof callback === 'function' ? callback : function() {};
                },
                getTemplate: function(settings) {
                    var html = '<div class="' + this.selector + ' ' + settings.type + '" data-index="' + settings.index + '" style="' + this.styles.notification + this.styles[settings.type] + '"><p style="' + this.styles.title + '"><span class="icon dashicons ' + settings.icon + '" style="' + this.styles.icon + '"></span>' + settings.args.title + '</p><p style="' + this.styles.subtitle + '">' + settings.args.subtitle + '</p></div>';

                    return html;
                },
                show: function(args) {

                    // get type
                    var type = args.type;

                    // get callbacks
                    var callbacks = this.getCallbacks(args.callbacks);

                    // initialize, if not initialized already
                    if ( ! this.exists() ) {
                        this.init();
                    }

                    // set default notification type
                    if ( ! icons.hasOwnProperty(type) ) {
                        type = 'info';
                    }

                    // set notification settings
                    var settings = {
                        args: {
                            title: args.title,
                            subtitle: args.subtitle
                        },
                        type: type,
                        icon: icons[type],
                        index: $('.' + this.selector).length
                    };

                    // get notification html
                    var html = this.getTemplate(settings);

                    // append notification to wrapper
                    $('.' + this.wrapper).append(html);

                    // show notification
                    var $notification = $('.' + this.selector + '[data-index="' + settings.index + '"]');
                    $notification.show();

                    // execute global show
                    show();

                    // execute local show
                    callbacks.show();

                    // call hide action
                    this.hide(settings.index, callbacks.hide);
                },
                hide: function(index, callback) {
                    var that = this;

                    setTimeout(function() {
                        if ( $('.' + that.selector + '[data-index="' + index + '"]:hover').length != 0 ) {

                            // call hide recursively
                            that.hide(index, callback);
                        } else {

                            // hide notification
                            var $notification = $('.' + that.selector + '[data-index="' + index + '"]');
                            $notification.hide();

                            // execute global hide
                            hide();

                            // execute local hide
                            callback();
                        }
                    }, hideAfter);
                }
            }
        })();


        // @public notify
        var notify = function(args) {

            // set args
            args = typeof args === 'object' ? args : {};

            // set delay
            var delay = typeof args.delay === 'number' && args.delay > 0 ? args.delay : 0;

            // show notification on event, if client has defined an event
            if ( typeof args.showOnEvent !== 'undefined' && args.showOnEvent.length > 0 ) {
                $(document).on(args.showOnEvent, function() {
                    // show notification with delay
                    setTimeout(function() {
                        notification.show(args);
                    }, delay);
                });
            } else {
                // show notification with delay
                setTimeout(function() {
                    notification.show(args);
                }, delay);
            }

            // enable cascade
            return this;
        };


        // @public init
        var init = function(args) {

            // configure args
            args = typeof args === 'object' ? args : {};

            // update show callback, if the client has provided one
            if ( typeof args.show === 'function' ) {
                show = args.show;
            }

            // update hide callback, if the client has provided one
            if ( typeof args.hide === 'function' ) {
                hide = args.hide;
            }

            // update icons, if the client has provided icons
            if ( typeof args.icons === 'object' ) {
                icons = args.icons;
            }

            // update hideAfter, if the client has provided one
            if ( typeof args.hideAfter === 'number' ) {
                hideAfter = args.hideAfter;
            }
        };

        // notify
        return {
            init: init,
            notify: notify
        };
    })();
})( jQuery );