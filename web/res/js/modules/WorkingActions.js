"use strict";

class WorkingActions extends Tools
{
    constructor()
    {
        super();
    }

    init()
    {
        let selectorWorkingStart = '#modal-working-start',
            selectorWorkingEnd = '#modal-working-end';

        this.injectDateTimeIntoModal(this, selectorWorkingStart);
        this.injectDateTimeIntoModal(this, selectorWorkingEnd);

        this.registerSaveButtonEvent(this, selectorWorkingStart, 'POST', Tools.getBaseUrl('working/start'));
        this.registerSaveButtonEvent(this, selectorWorkingEnd, 'PUT', Tools.getBaseUrl('working/end'));

        this.registerDropDownButtonEvent(this, selectorWorkingStart);
        this.registerDatePickerEvent(selectorWorkingStart);
    }

    /**
     * @param {WorkingActions} _this
     * @param {String} _selector
     */
    injectDateTimeIntoModal(_this, _selector)
    {
        $(_selector).on('show.bs.modal', function () {
            let date = new Date();
            $(_selector).find('input[name="workingTime"]').val(date.toLocaleTimeString().substring(0, 5));
            $(_selector).find('input[name="workingDate"]').val(date.toLocaleDateString(_this.DATE_LOCALE, _this.DATE_OPTIONS));
        });
    }

    /**
     * @param {WorkingActions} _this
     * @param {String} _selector
     * @param {String} _method
     * @param {String} _url
     */
    registerSaveButtonEvent(_this, _selector, _method, _url)
    {
        $(_selector).find('button[name="save"]').off().on('click', function () {
            $.ajax({
                url: _url,
                data: $(_selector).find('form').serialize(),
                dataType: 'json',
                type: _method
            }).done(function (_response) {
                _this.parseResult(_response);
            }).fail(function () {
                Tools.showErrorByCode(_this.ERROR_CODE_UNDEFINED);
            }).always(function () {
                $(_selector).modal('hide');
            });
        });
    }

    /**
     * @param {String} _selector
     */
    registerDatePickerEvent(_selector)
    {
        $(_selector).on('hidden.bs.modal', function () {
            $(this).find('input.datepicker').val('');
        }).on('show.bs.modal', function () {
            $(this).find('input.datepicker').datetimepicker({
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 1,
                daysOfWeekDisabled: [0, 6]
            })
        });
    }

    /**
     * @param {WorkingActions} _this
     * @param {String} _selector
     */
    registerDropDownButtonEvent(_this, _selector)
    {
        $(_selector).find('.dropdown-menu a').off().on('click', function (_event) {
            /**
             * @var {Object} workingModes
             * @property {Number} WORKING_MODE_DEFAULT
             * @property {Number} WORKING_MODE_SICK
             * @property {Number} WORKING_MODE_VACATION
             * @property {Number} WORKING_MODE_HOLIDAY
             * @property {Number} WORKING_MODE_OVERTIME
             */
            let selectedElement = $(_event.currentTarget),
                workingMode = parseInt(selectedElement.attr('data-working-mode')),
                workingModes = Config.getWorkingModes();

            switch (workingMode) {
                case workingModes.WORKING_MODE_DEFAULT:
                    _this.showFormDateTime(_selector);
                    break;
                default:
                    _this.showFormDateOnly(_selector);
            }

            $(_selector).find('input[name="workingMode"]').val(workingMode);
            $(_selector).find('button.wide-dropdown').html(selectedElement.html());
        });
    }

    /**
     * @param {String} _selector
     */
    showFormDateOnly(_selector)
    {
        $(_selector).find('.modal-body .datetime').removeClass('hidden');
        $(_selector).find('.modal-body .date').addClass('hidden');
    }

    /**
     * @param {String} _selector
     */
    showFormDateTime(_selector)
    {
        $(_selector).find('.modal-body .date').removeClass('hidden');
        $(_selector).find('.modal-body .datetime').addClass('hidden');
    }

    /**
     * @param {Object} _response
     * @property {Number} code
     * @property {Array} list
     * @property {Array} statistics
     */
    parseResult(_response)
    {
        if (_response.code === 0) {
            new WorkingList().prepareList(_response);
        } else {
            Tools.showErrorByCode(_response);
        }
    }
}