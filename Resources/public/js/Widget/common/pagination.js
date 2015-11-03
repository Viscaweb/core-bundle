define(['require'], function (require) {

    var jQuery = require('jquery');

    (function ($) {
        $.pagination = function(el, options) {

            /*
             * To avoid scope issues, use 'base' instead of 'this'
             * to reference this class from internal events and functions.
             */
            var base = this;

            /*
             * Access to jQuery and DOM versions of element
             */
            base.$el = $(el);
            base.el = el;

            /*
             * Initialize the plugin
             */
            base.init = function () {
                base.options = $.extend({}, $.pagination.defaultOptions, options);
                base.domInit();
            };

            /**
             * Main method binding all the widget box
             */
            base.domInit = function () {};

            jQuery(el)
                .on('click', 'li a', function() {
                    if ($(this).hasClass('disabled') || $(this).hasClass('active')) {
                        return false;
                    }

                    base.setFieldValue($(this).attr('data-page'));

                    return false;
                });

            base.setFieldValue = function(value) {
                jQuery("input[id="+base.options.fieldId+"]", el)
                    .val(value)
                    .trigger('change');
            };

            base.getValue = function () {
                return jQuery("input[id="+base.options.fieldId+"]", el).val();
            };

            // Run initializer
            base.init();
        };

        $.pagination.defaultOptions = {
            fieldId: null
        };

        $.fn.pagination = function (options) {
            return this.each(function () {
                (new $.pagination(this, options));
            });
        };
    }) (jQuery);
});
