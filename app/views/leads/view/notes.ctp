        <table cellpadding="0" cellspacing="0" class="stripeme">
            <tr>
                <th></th>
                <th>Type</th>
                <th>Subject</th>
                <th>Date</th>
                <th></th>
            </tr>
            <?php
            foreach ($events as $event) {
                echo '<tr>';
                    if ($event['Events']['type'] != "Note") {
                        if ($event['Events']['closed'])
                            echo '<td><img src="img/icons/accept.png" alt="Done"/></td>';
                        else
                            echo '<td><img src="img/icons/cross.png" alt="Not done"/></td>';
                    } else {
                        echo '<td></td>';
                    }
                    
                    echo '<td>'.$event['Events']['type'].'</td>';

                    echo '<td>'.$event['Events']['subject'].'</td>';

                    echo '<td>'.date('Y-m-d', strtotime($event['Events']['date'])).'</td>';
                    
                    echo '<td>';
                        if (!$event['Events']['closed'])
                            echo '<button class="button" onclick="opportunityDoneEvent('.$event['Events']['id'].')">Done</button>&nbsp;&nbsp;';
                        else
                            echo '<button class="button" onclick="opportunityDoneEvent('.$event['Events']['id'].')">Undone</button>&nbsp;&nbsp;';
                        echo '<button class="detailstrialrequest button" onclick="opportunityEditEvent('.$event['Events']['id'].')">Edit details</button>&nbsp;&nbsp;';
                        echo '<button class="button" onclick="opportunityDeleteEvent('.$event['Events']['id'].')">Delete</button>';
                    echo '</td>';
               echo '</tr>';
            }
            ?>
        </table>
        <!-- BEGIN mark as done -->
        <div class="modal" id="markasdone" title="Mark a task as done">
    	Do you confirm this task has been done / not done?
        </div>
        <!-- END mark as done -->
