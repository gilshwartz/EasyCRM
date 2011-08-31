<?php

App::import('Vendor', 'iCalcreator', array('file' => 'ical/iCalcreator.class.php'));

class ICalHelper extends

Helper {

    var $name = 'ICalHelper';
    var $errorCode = null;
    var $errorMessage = null;
    var $calendar;

    function create($name, $description='', $tz='US/Eastern') {
        $v = new vcalendar();
        $v->setConfig('unique_id', $name . '.' . 'planningforce.com');
        $v->setProperty('method', 'PUBLISH');
        $v->setProperty('x-wr-calname', $name . ' Calendar');
        $v->setProperty("X-WR-CALDESC", $description);
        $v->setProperty("X-WR-TIMEZONE", $tz);
        $this->calendar = $v;
    }

    function addEvent($start, $end=false, $summary, $description='', $extra=false) {
        $start = strtotime($start);

        $vevent = new vevent();
        if (!$end) {
            $end = $start + 24 * 60 * 60;
            $vevent->setProperty('dtstart', date('Ymd', $start), array('VALUE' => 'DATE'));
            $vevent->setProperty('dtend', date('Ymd', $end), array('VALUE' => 'DATE'));
        } else {
            $end = strtotime($end);

            $start = getdate($start);
            $a_start['year'] = $start['year'];
            $a_start['month'] = $start['mon'];
            $a_start['day'] = $start['mday'];
            $a_start['hour'] = $start['hours'];
            $a_start['min'] = $start['minutes'];
            $a_start['sec'] = $start['seconds'];

            $end = getdate($end);
            $a_end['year'] = $end['year'];
            $a_end['month'] = $end['mon'];
            $a_end['day'] = $end['mday'];
            $a_end['sec'] = $end['seconds'];
            $a_end['hour'] = $end['hours'];
            $a_end['min'] = $end['minutes'];

            $vevent->setProperty('dtstart', $a_start);
            $vevent->setProperty('dtend', $a_end);
        }
        $vevent->setProperty('summary', $summary);
        $vevent->setProperty('description', $description);
        if (is_array($extra)) {
            foreach ($extra as $key => $value) {
                $vevent->setProperty($key, $value);
            }
        }
        $this->calendar->setComponent($vevent);
    }

    function getCalendar() {
        return $this->calendar;
    }

    function render() {
        $this->calendar->returnCalendar();
    }

}

?>