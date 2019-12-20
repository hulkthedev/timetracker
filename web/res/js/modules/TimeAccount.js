"use strict";

class TimeAccount extends Tools
{
    constructor()
    {
        super();
    }

    init()
    {
        let selector = '#modal-time-account';

        this.loadTimeAccountWhenDialogIsOpen(this, selector);
        this.initSaveButtonEvent(this, selector);

        this.registerDropDownButtonEvent(this, selector);
        this.registerDatePickerEvent(selector);
    }

    /**
     * @param {TimeAccount} _this
     * @param {String} _selector
     */
    loadTimeAccountWhenDialogIsOpen(_this, _selector)
    {
        $(_selector).on('show.bs.modal', function () {
            $.ajax({
                url: Tools.getBaseUrl('timeaccount/get'),
                dataType: 'json',
                type: 'GET'
            }).done(function (_response) {
                if (_response.code === 0) {
                    _this.injectTimeAccountIntoModal(_selector, _response);
                } else {
                    Tools.showErrorByCode(_response.code);
                }
            }).fail(function () {
                Tools.showErrorByCode(_this.ERROR_CODE_UNDEFINED);
            });
        });
    }

    /**
     * @param {String} _selector
     * @param {Object} _response
     * @property {Number} code
     * @property {Object} timeAccount
     * @property {Array} timeAccount.overtimeDto
     * @property {Object} timeAccount.overtimeLeft
     * @property {Number} timeAccount.overtimeIsBalanced
     */
    injectTimeAccountIntoModal(_selector, _response)
    {
        let timeAccount = _response.timeAccount.overtimeLeft;
        if (_response.timeAccount.overtimeIsBalanced === -1) {
            timeAccount = '-' + timeAccount;
        }

        let form = $(_selector).find('form'),
            overtimeElement = form.find('blockquote span');

            overtimeElement.html(timeAccount).removeClass('balanced-status--negative balanced-status--positive');

        switch (_response.timeAccount.overtimeIsBalanced) {
            case -1:
                overtimeElement.addClass('balanced-status--negative');
                break;
            case 1:
                overtimeElement.addClass('balanced-status--positive');
                break;
            default:
                break;
        }
    }

    /**
     * @param {TimeAccount} _this
     * @param {String} _selector
     */
    initSaveButtonEvent(_this, _selector)
    {
        $(_selector).find('button[name="save"]').off().on('click', function () {
            $.ajax({
                url: Tools.getBaseUrl('timeaccount/add'),
                data: $(_selector).find('form').serialize(),
                dataType: 'json',
                type: 'PUT'
            }).done(function (_response) {
                _this.parseResult(_response);
            }).fail(function () {
                Tools.showErrorByCode(_this.ERROR_CODE_UNDEFINED);
            }).always(function () {
                $(_selector).modal('hide')
            });
        });
    }

    /**
     * @param {TimeAccount} _this
     * @param {String} _selector
     */
    registerDropDownButtonEvent(_this, _selector)
    {
        $(_selector).find('.dropdown-menu a').off().on('click', function (_event) {

            let selectedElement = $(_event.currentTarget),
                overtimeValue = selectedElement.attr('data-overtime-value');

            _this.showFormDateTime(_selector, overtimeValue);

            $(_selector).find('input[name="overtimeValue"]').val(overtimeValue);
            $(_selector).find('button.wide-dropdown').html(selectedElement.html());
        });
    }

    /**
     * @param {String} _selector
     * @param {String} _overtimeValue
     */
    showFormDateTime(_selector, _overtimeValue)
    {
        let element = $(_selector).find('.modal-body .overtime-manual');

        if (_overtimeValue.toLowerCase() === 'manual') {
            element.removeClass('hidden');
        } else {
            element.addClass('hidden');
        }
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