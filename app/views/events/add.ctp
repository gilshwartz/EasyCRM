<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form action="#" id="event2add">
            <table>
                <tr>
                    <td>Type of event</td>
                    <td>
                        <select name="data[Event][type]" onchange="onEventTypeChange();">
                            <?php
                            foreach ($types as $type) {
                                echo '<option>' . $type . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr id="eventdatestart">
                    <td>Start :</td>
                    <td>
                        <input id="startdate" type="text" name="data[Event][start_date]" value="<?php echo date('Y-m-d'); ?>"/>
                        <?php
                            echo '<select style="width: 60px;" name="data[Event][start_hour]">';
                            for ($i = 0; $i < 24; $i++)
                                for ($j = 0; $j < 60; $j = $j + 5)
                                    if ($j == 0 && sprintf('%02d', $i - 1) == date('H'))
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
                            <input id="enddate"  type="text" name="data[Event][end_date]" value="<?php echo date('Y-m-d'); ?>"/>
                        <?php
                            echo '<select style="width: 60px;" name="data[Event][end_hour]">';
                            for ($i = 0; $i < 24; $i++)
                                for ($j = 0; $j < 60; $j = $j + 5)
                                    if ($j == 0 && sprintf('%02d', $i - 2) == date('H'))
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
                            foreach ($users as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Contact Attendee</td>
                    <td>
                        <input id="infocontact" type="text" class="editable"/>
                        <input type="hidden" name="data[EventsContact][contacts_id]"/>
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
                    <td><input type="text" name="data[Event][subject]" class="editable"/></td>
                </tr>
                <tr>
                    <td>Comments</td>
                    <td><textarea cols="30" rows="5" name="data[Event][message]" ></textarea></td>
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
        </script>
    </body>
</html>
