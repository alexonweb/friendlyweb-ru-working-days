<?



class Calendar extends DateTime {

    private $_calendarDir = "../Russian/"; # где лежат все календари в формате JSON
    private $_calendar;
    private $day;
    private $month;
    private $year;


    public function setDay($datetime) {

        $datetime = new DateTime($datetime);

        $this->day      = $datetime->format("j");
        $this->month    = $datetime->format("F");
        $this->year     = $datetime->format("Y");

    }

    public function setCalendarDir($dir) {
        // установить дирректорую для календарей
    }

    
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


    private function matchingOfNum($number1, $number2) {
        if ($number1 == $number2) {
            return true;
        } else {
            return false;
        }
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
    *
    */
    public function getHolidayDescription() {

        $current_month = $this->calendar->{$this->month};
        
        foreach ($current_month as $day_number => $day) {
        
            if ( $this->checkRange($day_number, $this->day) ) {

                return $this->calendar->{$this->month}->{$day_number}->n;

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


        } else {

            echo "error Calendar not found";

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


$vac = new Calendar;

$vac->setDay("2017-02-23");

if ( $vac->isHoliday() ) {
    echo "выходной - " . $vac->getHolidayDescription();
};

if ($vac->isPreHoliday() ) {
    echo "сокращенный день";
}



?>
