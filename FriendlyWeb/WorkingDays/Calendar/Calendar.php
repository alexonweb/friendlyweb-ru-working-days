<?php
/**
 * Alexander Dalle
 * dalle@criptext.com
 * 
 */

namespace FriendlyWeb;

use DateTime;

class Calendar extends DateTime 
{

    // где лежат все календари в формате JSON
    private $_calendarDir = "data" . DIRECTORY_SEPARATOR . "russian" . DIRECTORY_SEPARATOR; 
    private $_calendar = null;
    private $day = null;
    private $month = null;
    private $year = null;
    private $i18n = array(
        "error_file" => "Календарь не найден! Проверьте правильно ли указана директория.",
        "holiday" => "Выходной день"
    );

    /**
     * Метод проверят входит ли в диапазон чисел (Например, "1-5") число
     * Возвращает boolean
     */
    private function checkRange($range, $number)
    {

        $range = explode('-', $range);

        $range[1] = isset($range[1]) ?: null;

        if ($range[1]) {

            for ($i = $range[0]; $i <= $range[1]; $i++) {

                if ($i == $number) {

                    return true;

                }

            }

        } else {

            if ($range[0] == $number) {

                return true;

            }

        }

    }

    /**
     * Метод устаналивает в соотвествии с установленным годом
     * Возвращает boolean
     */
    private function setCalendar() 
    {

        if (!$this->day || !$this->month || !$this->year) {

            $this->setDay("now");

        }

        $calendarFile = $this->_calendarDir . $this->year . ".json";

        if (file_exists($calendarFile)) {

          $contents = file_get_contents($calendarFile);

          $obj = json_decode($contents);

          $this->calendar = $obj;

          return true;

        } else {

            echo $this->i18n['error_file'];

            return false;

        }

    }


    /**
     * Метод проверят выходной день $preHoliday для сокращенного дня
     * Возвращает boolean
     */
    private function isRestDay($preHoliday = false) 
    {

        if ($this->setCalendar()) {

            if ($current_month = $this->calendar->{$this->month}) {

                foreach ($current_month as $day_number => $day) {

                    if ($this->checkRange($day_number, $this->day)) {

                        if (($this->calendar->{$this->month}->{$day_number}->rest) and (!$preHoliday)) {

                            return true;

                        }

                        if ((!$this->calendar->{$this->month}->{$day_number}->rest) and ($preHoliday)) {

                            return true;

                        }

                    }

                }

            }

        }

    }

    /**
     * Метод устаналивает дату
     */
    public function setDay($datetime = 'NOW') 
    {
        $datetime = new DateTime($datetime);
        $this->day = $datetime->format("j");
        $this->month = $datetime->format("F");
        $this->year = $datetime->format("Y");
    }

    /**
     * Метод устанавливает директорию с калнедарями
     */
    public function setCalendarDir($dir) 
    {
        $this->_calendarDir = $dir;
    }

    /**
     * 
     */
    public function getHolidayDescription() 
    {

        if ($this->isHoliday()) {

            $holidayDescr = $this->getDescriptionInRange();

            if (!$holidayDescr) {

                return $this->i18n['holiday'];

            } else {

                return $holidayDescr;

            }

        }

    }

    /**
     * Возвращает описание выходного дня в диапазоне дат (например, "7-8")
     */
    private function getDescriptionInRange()
    {

        $month = (array)$this->calendar->{$this->month};

        foreach ($month as $days => $obj) {

            if ($this->checkRange($days, $this->day)) {

                return $obj->n;

                break;

            }

        }

    }

    /*
    * Метод возвращает выходной день или нет
    * Возвращает boolean
    */
    public function isHoliday()
    {

        return $this->isRestDay(false);

    }

    /**
     * Метод возвращает сокращенный день или нет
     * Возвращает boolean
     */
    public function isPreHoliday()
    {

        return $this->isRestDay(true);

    }

}

?>
