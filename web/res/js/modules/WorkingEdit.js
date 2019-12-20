"use strict";

class WorkingEdit extends Tools
{
    constructor()
    {
        super();
    }

    init()
    {
        let selector = '#modal-working-edit';

        this.loadWorkingDayWhenDialogIsOpen(this, selector);

        this.initSaveButtonEvent(this, selector);
        this.initRealTimeDifferenceCalculator(this, selector);
    }

    /**
     * @param {WorkingEdit} _this
     * @param {String} _selector
     */
    loadWorkingDayWhenDialogIsOpen(_this, _selector)
    {
        $(_selector).on('show.bs.modal', function (_event) {
            let workingDate = $(_event.relatedTarget).attr('data-working-date');

            $.ajax({
                url: Tools.getBaseUrl('working/get/' + workingDate),
                dataType: 'json',
                type: 'GET'
            }).done(function (_response) {
                if (_response.code === 0) {
                    _this.injectWorkingDayIntoModal(_selector, _response);
                } else {
                    Tools.showErrorByCode(_response.code);
                }
            }).fail(function () {
                Tools.showErrorByCode(_this.ERROR_CODE_UNDEFINED);
            });
        });
    }

    /**
     * @param {WorkingEdit} _this
     * @param {String} _selector
     */
    initSaveButtonEvent(_this, _selector)
    {
        $(_selector).find('button[name="save"]').off().on('click', function () {
            $.ajax({
                url: Tools.getBaseUrl('working/update'),
                data: $(_selector).find('form').serialize(),
                dataType: 'json',
                type: 'PUT'
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

    /**
     * @param {WorkingEdit} _this
     * @param {String} _selector
     */
    initRealTimeDifferenceCalculator(_this, _selector)
    {
        let timeout = 500; // 0.5 sec.

        $(_selector).find('input[name="workingStartTime"], input[name="workingEndTime"]').off().on('keyup', _this.debounce(function() {
            $.ajax({
                url: Tools.getBaseUrl('recalculate/time/difference/realtime'),
                data: $(_selector).find('form').serialize(),
                dataType: 'json',
                type: 'POST'
            }).done(function (_response) {
                _this.showRightCalculatingStatus(_selector, _response);
            });
        }, timeout));
    }

    /**
     * @param {Function} func
     * @param {Number} wait
     * @param immediate
     * @return {Function}
     *
     * @see https://davidwalsh.name/javascript-debounce-function
     */
    debounce(func, wait, immediate)
    {
        let timeout;

        return function() {
            let context = this,
                args = arguments;

            let later = function() {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            };

            let callNow = immediate && !timeout;

            clearTimeout(timeout);
            timeout = setTimeout(later, wait);

            if (callNow) {
                func.apply(context, args);
            }
        };
    }

    /**
     * @param {String} _selector
     * @param {Object} _response
     * @property {Number} code
     * @property {Object} workingDay
     * @property {Number} id
     * @property {String} weekday
     * @property {String} date
     * @property {String} workingStart
     * @property {String} workingEnd
     * @property {String} timeDifference
     * @property {Number} timeDifferenceIsBalanced
     * @property {Number} workingMode
     */
    injectWorkingDayIntoModal(_selector, _response)
    {
        let workingDay = _response.workingDay;

        let form = $(_selector).find('form');
            form.find('input[name="workingDate"]').val(workingDay.date);
            form.find('input[name="workingStartTime"]').val(workingDay.workingStart);
            form.find('input[name="workingEndTime"]').val(workingDay.workingEnd);
            form.find('input[name="workingDifference"]').val(workingDay.timeDifference);

        this.showRightCalculatingStatus(_selector, workingDay);
    }

    /**
     * @param {Object} _selector
     * @param {Object} _response
     * @property {Number} code
     * @property {String} timeDifference
     * @property {Number} timeDifferenceIsBalanced
     */
    showRightCalculatingStatus(_selector, _response)
    {
        let timeDifference = _response.timeDifference;
        if (_response.timeDifferenceIsBalanced === -1) {
            timeDifference = '-' + timeDifference;
        }

        let form = $(_selector).find('form');
            form.find('input[name="workingDifference"]').val(timeDifference)
                .removeClass('balanced-status--negative balanced-status--positive');

        switch (_response.timeDifferenceIsBalanced) {
            case -1:
                form.find('input[name="workingDifference"]').addClass('balanced-status--negative');
                break;
            case 1:
                form.find('input[name="workingDifference"]').addClass('balanced-status--positive');
                break;
            default:
                break;
        }
    }
}