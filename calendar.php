<?php

namespace Calendar;

use DateTime;

class Calendar extends DateTime {

    // где лежат все календари в формате JSON
    private $_calendarDir = "data" . DIRECTORY_SEPARATOR . "russian" . DIRECTORY_SEPARATOR; 
    private $_calendar;
    private $day;
    private $month;
    private $year;
    private $i18n = array(
        "holiday" => "Выходной день"
    );

    /**
     * Метод сравнивает два числа
     * Возвращает boolean
    */
    private function matchingOfNum($number1, $number2) {

        return ($number1 == $number2) ? true : false;

    }

    /**
     * Метод проверят входит ли в диапазон чисел (Например, "1-5") число
     * Возвращает boolean
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

    /**
     * Метод устаналивает в соотвествии с установленным годом
     * Возвращает boolean
     */
    private function setCalendar() {

        // Если дата не установлена, устаналиваем сегоднящий день
        if ( !$this->day || !$this->month || !$this->year ) {

            $this->setDay("now");

        }

        // получаем календарь на текущий год
        $calendarFile = $this->_calendarDir . $this->year . ".json";

        if ( file_exists( $calendarFile ) ) {

          $contents = file_get_contents($calendarFile);

          $obj = json_decode($contents);

          $this->calendar = $obj;

          return true;

        } else {

            echo "Календарь не найден! Проверьте правильно ли указана директория.";

        }

    }


    /**
     * Метод проверят выходной день $preHoliday для сокращенного дня
     * Возвращает boolean
     */
    private function isRestDay ($preHoliday = false) {

        if ( $this->setCalendar() ) {

            if ( $current_month = $this->calendar->{$this->month} ) {

                foreach ( $current_month as $day_number => $day ) {

                    if ( $this->checkRange($day_number, $this->day) ) {

                        if ( ( $this->calendar->{$this->month}->{$day_number}->rest ) and ( !$preHoliday ) ) {

                            return true;

                        }

                        if ( ( !$this->calendar->{$this->month}->{$day_number}->rest ) and ( $preHoliday ) ) {

                            return true;

                        }

                    }

                }

            }

        }

    }


    /**
     * Мето устаналивает дату
     */
    public function setDay($datetime = "now") {
        $datetime = new DateTime($datetime);
        $this->day      = $datetime->format("j");
        $this->month    = $datetime->format("F");
        $this->year     = $datetime->format("Y");
    }

    /**
     * Метод устанавливает директорию с калнедарями
     */
    public function setCalendarDir($dir) {
        $this->_calendarDir = $dir;
    }

    /**
     * 
     */
    public function getHolidayDescription() {

        if ( $this->isHoliday() ) {

            $holidayDescr = $this->calendar->{$this->month}->{$this->day}->n;

            if ( !$holidayDescr ) {

                return $this->i18n['holiday']; // @todo перделать в MVC вид

            } else {

                return $holidayDescr;

            }

        }

    }

    /*
    * Метод возвращает выходной день или нет
    * Возвращает boolean
    */
    public function isHoliday() {

        return $this->isRestDay(false);

    }

    /**
     * Метод возвращает сокращенный день или нет
     * Возвращает boolean
     */
    public function isPreHoliday() {

        return $this->isRestDay(true);

    }

}

?>