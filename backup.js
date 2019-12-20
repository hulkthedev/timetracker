//function prepareTimeToGoHomeCountdown($week)
//{
//    if ($('li.page-item.next').hasClass('disabled')) {
//        var list = getListByWeek($week),
//            arrayList = Object.values(list),
//            todayEntry = arrayList[arrayList.length - 2];
//
//        if (todayEntry.end === '00:00:00') {
//            var start = todayEntry.start.split(':'),
//                todayStart = new Date();
//                todayStart.setHours(parseInt(start[0]) + 1);
//                todayStart.setMinutes(parseInt(start[1]));
//
//            const WORKING_TIME_PER_DAY_IN_MINUTES = 462;
//            const BREAKING_TIME_PER_DAY_IN_MINUTES = 30;
//
//            var todayEnd = new Date(todayStart);
//                todayEnd.setMinutes(todayEnd.getMinutes() + WORKING_TIME_PER_DAY_IN_MINUTES);
//                todayEnd.setMinutes(todayEnd.getMinutes() + BREAKING_TIME_PER_DAY_IN_MINUTES);
//
//            var currentTime = new Date();
//                currentTime.setHours(currentTime.getHours() + 1);
//
//            var restZeit = ((todayEnd.getTime() - currentTime.getTime()) / 60000) / 60;
//
//            countdown(decimalToTime(restZeit));
//        }
//    }
//}

//function getCurrentWeekNumber()
//{
//    var now = new Date(),
//        start = new Date(now.getFullYear(), 0, 1);
//
//    return Math.ceil((now.getTime() - start.getTime()) / (1000 * 3600 * 24 * 7));
//}

//function countdown($time)
//{
//    var countdown = setInterval(function () {
//        var element = $('table.working-time tbody tr:nth-last-child(2)').find('span.label');
//        element.html('');
//
//        if (--$time) {
//            element.html($duration);
//        } else {
//            element.html('GO HOME!');
//            clearInterval(countdown);
//        }
//    }, 1000);
//}

//function decimalToTime($decimal)
//{
//    var hour = Math.floor(Math.abs($decimal)),
//        minutes = Math.floor((Math.abs($decimal) * 60) % 60);
//
//    return (hour < 10 ? '0' : '') + hour + ':' + (minutes < 10 ? '0' : '') + minutes;
//}
//    var timeAccount = ($response.config.TIME_ACCOUNT_BALANCED === 0)
//        ? '-' + $response.config.TIME_ACCOUNT
//        : $response.config.TIME_ACCOUNT;
//
//    $('header nav p.total-balance b').html('').html(timeAccount);

//private function calculateNewTimeAccount(bool $isBalancedToday, int $differenceInMinutes): array
//{
//    $config = $this->configRepository->getConfig();
//    $isTimeAccountBalanced = $config->TIME_ACCOUNT_BALANCED;
//    $timeAccount = explode(':', $config->TIME_ACCOUNT);
//
//    $timeAccountCalculator = new TimeCalculationService();
//    $timeAccountCalculator->add((int)$timeAccount[0], (int)$timeAccount[1]);
//
//    if (true === $isTimeAccountBalanced && true === $isBalancedToday) {
//        $timeAccountCalculator->add(0, $differenceInMinutes);
//    } else {
//        $timeAccountCalculator->sub(0, $differenceInMinutes);
//    }
//
//    if (false === $isTimeAccountBalanced && $timeAccountCalculator->isBalanced()) {
//        $isTimeAccountBalanced = 0;
//    }
//
//    return [
//        'timeAccount' => $timeAccountCalculator->getFormattedResult(false),
//        'timeAccountBalanced' => $isTimeAccountBalanced
//];
//}