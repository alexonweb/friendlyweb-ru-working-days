<?php

namespace Calendar;

include_once('calendar.php');

$calendar = new Calendar;

$calendar->setDay("2017-02-22");

$calendar->setCalendarDir("data/russian/");

if ( $calendar->isHoliday() ) {
    echo "выходной - " . $calendar->getHolidayDescription();
};

if ($calendar->isPreHoliday() ) {
    echo "сокращенный день";
}



?>