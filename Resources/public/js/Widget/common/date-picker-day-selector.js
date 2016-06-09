/* jshint browser:true, node:true, strict:true, noempty:true, noarg:true, eqeqeq:true, browser:true, bitwise:true, curly:true, undef:true, nonew:true, forin:true */
/* global module:true, define: true, exports: true, jQuery: true */
(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery', 'jquery-ui'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        var $ = require('jquery');
        window.jQuery = $;
        require('jquery-ui');
        factory($);
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    var DatePickerSelector = function (el, options) {
        this.el = el;
        this.$el = $(el);
        this.options = $.extend({}, DatePickerSelector.defaultOptions, options);

        this.init();
    };

    DatePickerSelector.prototype.init = function () {
        this.$el
            .on('click', '.btn-datepicker-open', function () {
                this.getDatePicker().datepicker('show');
            }.bind(this))
            .on('click', '.btn-datepicker-day-after', function () {
                this.dateIncreaseByDay(+1);
            }.bind(this))
            .on('click', '.btn-datepicker-day-before', function () {
                this.dateIncreaseByDay(-1);
            }.bind(this));
    };

    DatePickerSelector.prototype.getDatePicker = function () {
        var $input = this.$el.find('.input-datepicker');

        if ($input.hasClass('hasDatepicker')) {
            return $input;
        }

        /*
         * Else initialize datepicker
         */
        $input.datepicker({
            dateFormat: this.options.dateFormat,
            onSelect : this.onSelectDate.bind(this),
            beforeShow: function(event, ui) {
                setTimeout(function(){this.onBeforeShow(event, ui);}.bind(this), 1);
            }.bind(this)
        });

        var datePickerLocale = $input.data('locale');
        if (typeof(datePickerLocale) === 'string'){
            $.datepicker.setDefaults($.datepicker.regional[datePickerLocale]);
        }

        var value = $input.val();
        if (typeof(value) === 'string') {
            $input.datepicker('setDate', value);
        }

        return $input;
    };

    DatePickerSelector.prototype.onSelectDate = function (dateText, ui) {
        var dpInput = ui.input;

        // Trigger the event used by the widget refresh
        dpInput.trigger('change');

        this.updateCaption();
    };

    DatePickerSelector.prototype.updateCaption = function () {
        // Get the current datepicker's format
        var defaultDateFormat = this.getDatePicker().datepicker('option', 'dateFormat');

        // Change the format to get the version used for the displaying
        var dateTextVersion = this.getDatePicker().datepicker('option', 'dateFormat', 'D d M.').val();
        this.$el.find('.text-selected-date').html(dateTextVersion);

        // Reset the format
        this.getDatePicker().datepicker('option', 'dateFormat', defaultDateFormat);
    };

    DatePickerSelector.prototype.onBeforeShow = function (event, ui) {
        var dpDiv = ui.dpDiv;
        //var dpInput = ui.input;

        dpDiv.addClass('ui-datepicker-with-border');

        var position = this.$el.offset();
        var positionLeft = position.left;
        positionLeft+= this.$el.outerWidth(true);
        positionLeft-= dpDiv.outerWidth(true);

        var positionTop = position.top;
        positionTop+= this.$el.outerHeight(true);

        ui.dpDiv.css({
            left: positionLeft,
            top: positionTop
        });
    };

    DatePickerSelector.prototype.dateIncreaseByDay = function (days) {
        var $input = this.getDatePicker();
        var dateCurrent = $input.datepicker('getDate');
        var dateNext = new Date(dateCurrent.getTime() + days * 86400 * 1000);
        $input.datepicker('setDate', dateNext);
        $('.ui-datepicker-current-day', $input.dpDiv).trigger('click');
    };

    DatePickerSelector.defaultOptions = {
        dateFormat : 'yy-mm-dd'
    };


    $.fn.datePickerDaySelector = function (options) {
        var toReturn = this;
        var toReturnDefault = this.each( function () {
            var $this = $(this);
            var data = $this.data('app.datepicker.selector');

            if (!data) {
                $this.data('app.datepicker.selector', (data = new DatePickerSelector(this, options)));
            }

            if (typeof options === 'string') {
                toReturn = data[options]();
                return false;
            }
        });


        return (toReturn !== toReturnDefault) ? toReturn : toReturnDefault;
    };
}));
