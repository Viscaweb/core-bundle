/* jshint browser:true, node:true, strict:true, noempty:true, noarg:true, eqeqeq:true, browser:true, bitwise:true, curly:true, undef:true, nonew:true, forin:true */
/* global module:true, define: true, exports: true, jQuery: true */
'use strict';
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        factory(require('jquery'));
    } else {
        factory(jQuery);
    }
}(function ($) {

    $.dropDownWithButtonGroup = function (el, options) {

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
            base.options = $.extend({}, $.dropDownWithButtonGroup.defaultOptions, options);
            base.domInit();
        };

        /**
         * Main method binding all the widget box
         */
        base.domInit = function () {
        };

        /*
         * Events
         */
        $(el)
            .on('click', '.btn-next', function () {
                var selectedElement = base.getSelectedElement();
                var nextElement = selectedElement.next();
                if (nextElement.length > 0) {
                    base.setSelectedItem(selectedElement, nextElement);

                    $('.btn-previous', el).removeClass('disabled');

                    if (nextElement.next().length === 0) {
                        $(this).addClass('disabled');
                    }
                }

                return false;
            })
            .on('click', '.btn-previous', function () {
                var selectedElement = base.getSelectedElement();
                var prevElement = selectedElement.prev();
                if (prevElement.length > 0) {
                    base.setSelectedItem(selectedElement, prevElement);

                    $('.btn-next', el).removeClass('disabled');

                    if (prevElement.prev().length === 0) {
                        $(this).addClass('disabled');
                    }
                }

                return false;
            })
            .on('click', '.dropdown-menu a', function () {
                base.setFieldValue($(this).attr('data-value'));

                return false;
            });

        /*
         * Helpers
         */

        base.getValue = function () {
            return $("input[id=" + base.options.fieldId + "]", el).val();
        };

        base.getSelectedElement = function () {
            return $('.dropdown-menu li.selected', el);
        };

        base.setSelectedItem = function (selectedElement, dropDownItem) {
            $(selectedElement).removeClass('selected');
            $(dropDownItem).addClass('selected');

            var value = $('a', dropDownItem).attr('data-value');

            base.setFieldValue(value);
        };

        base.setFieldValue = function (value) {
            $("input[id=" + base.options.fieldId + "]", el)
                .val(value)
                .trigger('change');

            base.options.change(base.getValue());
        };

        // Run initializer
        base.init();
    };

    $.dropDownWithButtonGroup.defaultOptions = {
        change:  function (val) {
        },
        fieldId: null
    };

    $.fn.dropDownWithButtonGroup = function (options) {
        return this.each(function () {
            (new $.dropDownWithButtonGroup(this, options));
        });
    };
}));