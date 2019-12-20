"use strict";

class WorkingList extends Tools
{
    constructor()
    {
        super();
    }

    load()
    {
        let _this = this;

        $.ajax({
            url: Tools.getBaseUrl('working/listing'),
            dataType: 'json',
            type: 'GET',
            beforeSend: function () {
                _this.increaseProgressbarTo(25);
            }
        }).done(function (_response) {
            if (_response.code === 0) {
                _this.increaseProgressbarTo(50);
                _this.prepareList(_response);
            } else {
                _this.increaseProgressbarTo(100);
                Tools.showErrorByCode(_response);
            }
        }).fail(function () {
            _this.increaseProgressbarTo(100);
            Tools.showErrorByCode(_this.ERROR_CODE_UNDEFINED);
        });
    }

    /**
     * @param {Object} _response
     * @property {Array} list
     * @property {Array} statistics
     */
    prepareList(_response)
    {
        this.increaseProgressbarTo(75);

        let listLength = Object.keys(_response.list).length;

        if (listLength > 0) {
            this.saveList(_response.list);

            this.createWorkingTimeRowsByPageIndex(listLength);
            this.createPaginationByIndex(listLength);

            this.increaseProgressbarTo(100);

            let _this = this;
            setTimeout(function () {
                _this.showStatistics(_response.statistics);
                $('nav.working-time-pagination').removeClass('hidden');
            }, 1000);
        }

        setTimeout(function () {
            $('.progress').hide();
            $('table.working-time').removeClass('hidden');
        }, 1000);
    }

    /**
     * @param {Number} _index
     */
    createWorkingTimeRowsByPageIndex(_index)
    {
        /**
         * @type {Object} weekList
         * @property {Number} weekNr
         * @property {String} difference
         * @property {Number} differenceIsBalanced
         * @property {Array} workingDays
         */
        let weekList = WorkingList.getListByIndex(_index-1),
            rows = '';

        if (weekList.workingDays.length > 0) {
            let _this = this,
                workingModes = Config.getWorkingModes();

            /**
             * @type {Object} workingDay
             * @property {Number} id
             * @property {String} weekday
             * @property {String} date
             * @property {String} workingStart
             * @property {String} workingEnd
             * @property {String} timeDifference
             * @property {Number} timeDifferenceIsBalanced
             * @property {Number} workingMode
             */
            $.each(Object.values(weekList.workingDays), function (i, workingDay) {
                switch (workingDay.workingMode) {
                    case workingModes.WORKING_MODE_SICK:
                        rows += _this.getNonWorkingTimeRow('S I C K', 'sick', weekList.weekNr, workingDay);
                        break;
                    case workingModes.WORKING_MODE_VACATION:
                        rows += _this.getNonWorkingTimeRow('V A C A T I O N', 'vacation', weekList.weekNr, workingDay);
                        break;
                    case workingModes.WORKING_MODE_HOLIDAY:
                        rows += _this.getNonWorkingTimeRow('H O L I D A Y', 'holiday', weekList.weekNr, workingDay);
                        break;
                    case workingModes.WORKING_MODE_OVERTIME:
                        rows += _this.getNonWorkingTimeRow('O V E R T I M E', 'overtime', weekList.weekNr, workingDay);
                        break;
                    case workingModes.WORKING_MODE_DEFAULT:
                    default:
                        rows += _this.getWorkingTimeRow(weekList.weekNr, workingDay);
                }
            });

            rows += _this.getWeekBalanceRow(weekList.difference, weekList.differenceIsBalanced);
        }

        $('table.working-time tbody').html('').append(rows);
    }

    /**
     * @param {String} _modeText
     * @param {String} _modeClass
     * @param {Number} _weekNr
     * @param {Object} _workingDay
     * @property {Number} id
     * @property {String} weekday
     * @property {String} date
     * @property {String} workingStart
     * @property {String} workingEnd
     * @property {String} timeDifference
     * @property {Number} timeDifferenceIsBalanced
     * @property {Number} workingMode
     *
     * @return {String}
     */
    getNonWorkingTimeRow(_modeText, _modeClass, _weekNr, _workingDay)
    {
        return  '<tr class="'+ _modeClass + '">' +
                    '<th>' + _workingDay.id + '</th>' +
                    '<td class="align-center">' + _weekNr + '</td>' +
                    '<td>' + _workingDay.weekday + '</td>' +
                    '<td>' + _workingDay.date + '</td>' +
                    '<td colspan="2">' + _modeText + '</td>' +
                    '<td class="align-center"><span class="label label-default">' + _workingDay.timeDifference + '</span></td>' +
                    '<td class="align-center"><button type="button" class="btn btn-sm" data-toggle="modal" data-target="#modal-working-edit" data-working-date="' + _workingDay.date + '"><span class="glyphicon glyphicon-edit"></span></button></td>' +
                '</tr>';
    }

    /**
     * @param {Number} _weekNr
     * @param {Object} _workingDay
     * @property {Number} id
     * @property {String} weekday
     * @property {String} date
     * @property {String} workingStart
     * @property {String} workingEnd
     * @property {String} timeDifference
     * @property {Number} timeDifferenceIsBalanced
     * @property {Number} workingMode
     *
     * @return {String}
     */
    getWorkingTimeRow(_weekNr, _workingDay)
    {
        let status = 'default';
        switch (_workingDay.timeDifferenceIsBalanced) {
            case 1:
                status = 'success';
                break;
            case -1:
                status = 'danger';
                break;
        }

        return  '<tr>' +
                    '<th>' + _workingDay.id + '</th>' +
                    '<td class="align-center">' + _weekNr + '</td>' +
                    '<td>' + _workingDay.weekday + '</td>' +
                    '<td>' + _workingDay.date + '</td>' +
                    '<td>' + _workingDay.workingStart + '</td>' +
                    '<td>' + _workingDay.workingEnd + '</td>' +
                    '<td class="align-center"><span class="label label-' + status + '">' + _workingDay.timeDifference + '</span></td>' +
                    '<td class="align-center"><button type="button" class="btn btn-sm" data-toggle="modal" data-target="#modal-working-edit" data-working-date="' + _workingDay.date + '"><span class="glyphicon glyphicon-edit"></span></button></td>' +
                '</tr>';
    }

    /**
     * @param {String} _difference
     * @param {Number} _isBalanced
     *
     * @return {String}
     */
    getWeekBalanceRow(_difference, _isBalanced)
    {
        let status = 'default';
        switch (_isBalanced) {
            case 1:
                status = 'success';
                break;
            case -1:
                status = 'danger';
                break;
        }

        return  '<tr>' +
                    '<td colspan="6">&nbsp;</td>' +
                    '<td class="align-center"><span class="label label-' + status + '">' + _difference + '</span></td>' +
                    '<td>&nbsp;</td>' +
                '</tr>';
    }

    /**
     * @param {Object} _statistics
     * @property {Number} totalSickDays
     * @property {Number} totalVacationDays
     * @property {Number} totalOvertimeDays
     */
    showStatistics(_statistics)
    {
        let totalVacationDays = _statistics.totalVacationDays,
            totalSickDays = _statistics.totalSickDays;

        let selector = $('ul.nav-statistics');

        if (totalSickDays > 0) {
            selector.find('span.sick').html('').html(totalSickDays);
        }

        if (totalVacationDays > 0) {
            selector.find('span.vacation').html('').html(totalVacationDays);
        }
    }

    /**
     * @documentation https://esimakin.github.io/twbs-pagination
     * @param {Number} _index
     */
    createPaginationByIndex(_index)
    {
        let _this = this;

        $('.pagination').twbsPagination({
            startPage: _index,
            totalPages: _index,
            visiblePages: 4,
            next: '»',
            prev: '«',
            onPageClick: function (_event, _page) {
                _this.createWorkingTimeRowsByPageIndex(_page);
                this.totalPages = _page;
            }
        });
    }

    /**
     * @param {Number} _percent
     */
    increaseProgressbarTo(_percent)
    {
        $('.progress-bar').css({'width': _percent + '%'});
    }

    /**
     * @param {Array} _list
     */
    saveList(_list)
    {
        localStorage.setItem('list', JSON.stringify(_list));
    }

    /**
     * @return {Array}
     */
    static getList()
    {
        return JSON.parse(localStorage.getItem('list'));
    }

    /**
     * @param {Number} _index
     * @return {Object}
     */
    static getListByIndex(_index)
    {
        let list = WorkingList.getList();

        if (list[_index] !== undefined) {
            return list[_index];
        }

        return {};
    }
}