<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form action="#" id="event2edit">
            <table>
                <tr>
                    <td>Type of event</td>
                    <td>
                        <select name="data[Event][type]" onchange="onEventTypeChange();">
                            <?php
                            foreach ($types as $type) {
                                if ($type == $event['Event']['type'])
                                    echo '<option selected>' . $type . '</option>';
                                else
                                    echo '<option>' . $type . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr id="eventdatestart">
                    <td>Start :</td>
                    <td>
                        <input id="startdate" type="text" name="data[Event][start_date]" value="<?php echo date('Y-m-d', strtotime($event['Event']['start_date']));?>"/>
                        <?php
                            echo '<select style="width: 60px;" name="data[Event][start_hour]">';
                            for ($i = 0; $i < 24; $i++)
                                for ($j = 0; $j < 60; $j = $j + 5)
                                    if (sprintf('%02d:%02d', $i, $j) == date('H:i', strtotime($event['Event']['start_date'])))
                                        printf('<option selected>%02d:%02d</option>', $i, $j);
                                    else
                                        printf('<option>%02d:%02d</option>', $i, $j);
                            echo '</select>';
                        ?>
                    </td>
                </tr>
                <tr id="eventdateend">
                    <td>End :</td>
                    <td>
                        <input id="enddate" type="text" name="data[Event][end_date]" value="<?php echo date('Y-m-d', strtotime($event['Event']['end_date']));?>"/>
                        <?php
                            echo '<select style="width: 60px;" name="data[Event][end_hour]">';
                            for ($i = 0; $i < 24; $i++)
                                for ($j = 0; $j < 60; $j = $j + 5)
                                    if (sprintf('%02d:%02d', $i, $j) == date('H:i', strtotime($event['Event']['end_date'])))
                                        printf('<option selected>%02d:%02d</option>', $i, $j);
                                    else
                                        printf('<option>%02d:%02d</option>', $i, $j);
                            echo '</select>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>AM Attendees</td>
                    <td>
                        <select name="data[EventsUser][][users_id]" onchange="" size="5" multiple="true">
                            <?php
                            $usersId = array();
                            foreach ($event['EventsUser'] as $user)
                                array_push ($usersId, $user['users_id']);
                            
                            foreach ($users as $key => $value) {
                                if (in_array($key, $usersId))
                                    echo '<option selected value="' . $key . '">' . $value . '</option>';
                                else
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Contact Attendee</td>
                    <td>
                        <input id="infocontact" type="text" class="editable" value="<?php echo $contact['Contact']['fullname']; ?>"/>
                        <input type="hidden" name="data[EventsContact][contacts_id]" value="<?php echo $contact['Contact']['id']; ?>"/>
                        <script type="text/javascript">
                            $('#infocontact').autocomplete('contacts/autocomplete', {minChars : 3});
                            $("#infocontact").result(function(event, data, formatted) {
                                $("input:hidden[name='data[EventsContact][contacts_id]']").val(data[1]);
                            });
                        </script>
                    </td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td><input type="text" name="data[Event][subject]" value="<?php echo $event['Event']['subject']; ?>"/></td>
                </tr>
                <tr>
                    <td>Comments</td>
                    <td><textarea cols="30" rows="5" name="data[Event][message]"><?php echo $event['Event']['message']; ?></textarea></td>
                </tr>
            </table>
        </form>
        <script type="text/javascript" >
            function onEventTypeChange() {
                $('#eventdatestart').show();
                $('#eventdateend').show();
                switch ($('select[name="data[Event][type]"]').val()) {
                    case "Event" : break;
                    case "Call" : break;
                    case "Note" :
                        $('#eventdatestart').hide();
                        $('#eventdateend').hide();
                        break;
                    case "Task" :
                        $('#eventdatestart').hide();
                        //$('#eventdateend').hide();
                        break;
                }
            }
            onEventTypeChange();
        </script>
    </body>
</html>