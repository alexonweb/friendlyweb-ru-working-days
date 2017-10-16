<?php

// @todo setcalendar если не установлен
// @todo возвращает рабочий день!!


namespace Calendar;

use DateTime;

class Calendar extends DateTime {

    // где лежат все календари в формате JSON
    private $_calendarDir = "data" . DIRECTORY_SEPARATOR . "russian" . DIRECTORY_SEPARATOR; 
    private $_calendar;
    private $day;
    private $month;
    private $year;


    /**
     * Метод сравнивает два числа
     * Возвращает boolean
    */
    private function matchingOfNum($number1, $number2) {

        return ($number1 == $number2) ? true : false;

    }

    /* */
    private function setCalendar() {

        // получаем календарь на текущий год
        $calendarFile = $this->_calendarDir . $this->year . ".json";

        if ( file_exists( $calendarFile ) ) {
    
          $contents = file_get_contents($calendarFile);
    
          $obj = json_decode($contents);
    
        }

        $this->calendar = $obj;

    }



    /*
    * Метод возвращает массив со списком выходных дней для текущего месяца
    * return array
    */
    private function getHolidaysByMonth() {

        $this->setCalendar();

        $current_month = $this->calendar->{$this->month};

        foreach ($current_month as $day_number => $day) {

            $days_range = explode("-", $day_number); // "1-6" диапазон

            if ($days_range[1]) {

                for ($i = $days_range[0]; $i <= $days_range[1]; $i++) {

                    $holidays[] = $i;

                }

            } else {

                $holidays[] = $day_number;

            }

        }

        return $holidays;

    }


    /*
    *
    *
    */
    private function checkRange ($range, $number) {

        $range = explode("-", $range);

        if ($range[1]) {

                for ($i = $range[0]; $i <= $range[1]; $i++) {

                    /// есть ли число
                    if ( $this->matchingOfNum($i, $number) ) {
                        return true;
                    }

                }

        } else {

            if ( $this->matchingOfNum($range[0], $number) ) {
                return true;
            }

        }



    }
    /*
    *
    */
    private function isRestDay ($preHoliday = false) {

        $this->setCalendar();

        if ($this->calendar) {

            
            $current_month = $this->calendar->{$this->month};

            if ($current_month) {
                foreach ($current_month as $day_number => $day) {
                    
                        $zzz = $this->checkRange($day_number, $this->day);
        
                        if ($zzz) {
        
                            if ( ( $this->calendar->{$this->month}->{$day_number}->rest ) and ( !$preHoliday ) ) {
        
        
                            return true;
        
                            }
        
                            if ( ( !$this->calendar->{$this->month}->{$day_number}->rest ) and ( $preHoliday ) ) {
        
                            return true;
        
                            }
                        }
                    }
            }




        } else {

            echo "Календарь не найден! Проверьте правильно ли указана директория.";

        }

    }




/** */
    public function setDay($datetime = "now") {

        $datetime = new DateTime($datetime);

        $this->day      = $datetime->format("j");
        $this->month    = $datetime->format("F");
        $this->year     = $datetime->format("Y");

    }
/* */
    public function setCalendarDir($dir) {
        // установить дирректорую для календарей
        $this->_calendarDir = $dir;

    }

    





    /*
    *
    *
    */
    public function getHolidayDescription() {

        $current_month = $this->calendar->{$this->month};
        
        foreach ($current_month as $day_number => $day) {
        
            if ( $this->checkRange($day_number, $this->day) ) {

                $holidayDescr = $this->calendar->{$this->month}->{$day_number}->n;

                if (!$holidayDescr) {
                    return "Выходной день"; // @todo перделать в MVC вид
                } else {
                    return $holidayDescr;
                }

            }

        }

    }


    /*
    * Метод возвращает выходной день или нет
    * return boolean
    */
    public function isHoliday() {

        return $this->isRestDay(false);

    }

    public function isPreHoliday() {

        return $this->isRestDay(true);

    }



}





?>
