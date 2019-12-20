"use strict";

class Config extends Tools
{
    constructor()
    {
        super();
    }

    load()
    {
        let _this = this;

        $.ajax({
            url: Tools.getBaseUrl('config/get'),
            dataType: 'json',
            type: 'GET'
        }).done(function (_response) {
            _this.parseResult(_response);
        }).fail(function () {
            Tools.showErrorByCode(_this.ERROR_CODE_UNDEFINED);
        });
    }

    init()
    {
        let selector = '#modal-config';

        this.injectConfigIntoModal(selector);
        this.initSaveButtonEvent(this, selector);
    }

    /**
     * @param {Object} _response
     * @property {Number} code
     * @property {Object} config
     * @property {Object} workingModes
     */
    parseResult(_response)
    {
        if (_response.code === 0) {
            this.saveConfig(_response.config);
            this.saveWorkingModes(_response.workingModes);
        } else {
            Tools.showErrorByCode(_response);
        }
    }

    /**
     * @param {String} _selector
     */
    injectConfigIntoModal(_selector)
    {
        $(_selector).on('show.bs.modal', function () {
            let config = Config.getConfig();

            let form = $(_selector).find('form');
                form.find('input[name="breakTimePerDay1"]').val(config.BREAKING_TIME_PER_DAY_1);
                form.find('input[name="breakTimePerDay2"]').val(config.BREAKING_TIME_PER_DAY_2);
                form.find('input[name="workingTimePerDay"]').val(config.WORKING_TIME_PER_DAY);
                form.find('input[name="vacationDaysPerYear"]').val(config.VACATION_DAYS_PER_YEAR);
        });
    }

    /**
     * @param {Config} _this
     * @param {String} _selector
     */
    initSaveButtonEvent(_this, _selector)
    {
        $(_selector).find('button[name="save"]').off().on('click', function () {
            $.ajax({
                url: Tools.getBaseUrl('config/update'),
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
     * @param {Object} _config
     */
    saveConfig(_config)
    {
        localStorage.setItem('config', JSON.stringify(_config));
    }

    /**
     * @param {Object} _workingModes
     */
    saveWorkingModes(_workingModes)
    {
        localStorage.setItem('workingModes', JSON.stringify(_workingModes));
    }

    /**
     * @return {Object}
     * @property {String} BREAKING_TIME_PER_DAY_1
     * @property {String} BREAKING_TIME_PER_DAY_2
     * @property {String} WORKING_TIME_PER_DAY
     * @property {Number} VACATION_DAYS_PER_YEAR
     */
    static getConfig()
    {
        return JSON.parse(localStorage.getItem('config'));
    }

    /**
     * @return {Object}
     * @property {Number} WORKING_MODE_DEFAULT
     * @property {Number} WORKING_MODE_SICK
     * @property {Number} WORKING_MODE_VACATION
     * @property {Number} WORKING_MODE_HOLIDAY
     * @property {Number} WORKING_MODE_OVERTIME
     */
    static getWorkingModes()
    {
        return JSON.parse(localStorage.getItem('workingModes'));
    }
}