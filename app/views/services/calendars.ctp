<?php

$iCal->create('Calendar', 'PlanningForce CRM Calendar', 'Europe/Brussels');
foreach ($events as $event) {
    $start = $event['Event']['start_date'];
    $end = $event['Event']['end_date'];
    $summary = $event['Event']['type'] . ' : ' . $event['Event']['subject'];
    $description = $event['Event']['message'];
    $extra = array(
        'UID' => $event['Event']['id'],
        'organizer' => $event['Owner']['email']
    );
    $iCal->addEvent($start, $end, $summary, $description, $extra);
}
$iCal->render();
?>
