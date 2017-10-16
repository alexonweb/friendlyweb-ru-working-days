<?php

/**
 * Примеры использования производственного календаря
 */


// Подключаем каледарь
namespace Calendar;

include_once('calendar.php');

$calendar = new Calendar;

// $calendar->setCalendarDir("data/calendar/"); // только если директория с календарями измененна


// --------------------------
// Сегодня

echo "Сегодня ";

if ( $calendar->isHoliday() ) {

    echo "выходной (" . $calendar->getHolidayDescription() . ")";

} elseif ( $calendar->isPreHoliday() ) {

    echo "сокращенный день";

} else {

    echo "рабочий день";

}

echo "<hr />";

// --------------------------
// Дата

$date = "2017-02-23";

$calendar->setDay($date); // Устанавливаем дату

echo "23 февраля, 2017 - ";

if ( $calendar->isHoliday() ) {

    echo "выходной (" . $calendar->getHolidayDescription() . ")";

} elseif ( $calendar->isPreHoliday() ) {

    echo "сокращенный день";

} else {

    echo "рабочий день";

}




?>