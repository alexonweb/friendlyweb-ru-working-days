<?php

/**
 * Примеры использования производственного календаря
 */


// Подключаем каледарь

require 'FriendlyWeb/WorkingDays/WorkingDays.php';

$workingdays = new FriendlyWeb\WorkingDays();

$workingdays->setCalendarDir("FriendlyWeb/WorkingDays/data/russian/"); // если директория с календарями измененна


// --------------------------
// Сегодня

echo 'Сегодня ';

if ( $workingdays->isHoliday() ) {

    echo 'выходной (' . $workingdays->getHolidayDescription() . ')';

} elseif ( $workingdays->isPreHoliday() ) {

    echo 'сокращенный день';

} else {

    echo 'рабочий день';

}

echo '<hr />';

// --------------------------
// Дата

$date = '2022-03-08';

$workingdays->setDay($date); // Устанавливаем дату

echo '8 марта, 2022 - ';

if ( $workingdays->isHoliday() ) {

    echo 'выходной (' . $workingdays->getHolidayDescription() . ')';

} elseif ( $workingdays->isPreHoliday() ) {

    echo 'сокращенный день';

} else {

    echo 'рабочий день';

}




?>