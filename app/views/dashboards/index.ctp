<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    </head>

    <body>
        <div class="div1000" style="margin-top: 30px;">
            <!--<h1 style="text-align: right">Notifications</h1>-->
            <?php
            if ($newrequests > 0)
                echo '<p class="info1" id="newrequestbanner">';
            else
                echo '<p class="info1" id="newrequestbanner" style="display: none;">';
            ?>
            You have <a href="#" onclick="return loadPage('requests/newrequests')"><strong><?php echo $newrequests; ?></strong> new requests</a>
            </p>
            <?php
            if ($pendingrequests > 0)
                echo '<p class="info2" id="pendingrequestbanner">';
            else
                echo '<p class="info2" id="pendingrequestbanner" style="display: none;">';
            ?>
            You have <a href="#" onclick="return loadPage('requests/myrequests');"><strong><?php echo $pendingrequests; ?></strong>  new assigned</a> requests
            </p>
            <?php
            if ($newrequests == 0 && $pendingrequests == 0)
                echo '<p class="info3" id="norequestbanner">';
            else
                echo '<p class="info3" id="norequestbanner" style="display: none;">';
            ?>
            You don't have any pending request.
            </p>  
        </div>
        <h1 style="cursor: pointer;" onclick="loadPage('events/mytasks')">My Tasks</h1>
        <table id="tasks" class="stripeme" cellpadding="0" cellspacing="0" width="50%">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Opportunity</th>
                <th>Subject</th>
                <th></th>
            </tr>
            <?php
            foreach ($events as $event) {
                echo '<tr id="' . $event['Events']['id'] . '">';
                if ($event['Events']['start_date'] != '0000-00-00 00:00:00')
                    echo '<td>' . $event['Events']['start_date'] . '</td>';
                else
                    echo '<td></td>';
                echo '<td>' . $event['Events']['type'] . '</td>';
                echo '<td><a href="#"  onclick="return loadPage(\'leads/view/' . $event['Lead']['id'] . '\');">' . $event['Lead']['name'] . '</a></td>';
                echo '<td>' . $event['Events']['subject'] . '</td>';
                echo '<td>';
                echo '<button class="button" onclick="opportunityDoneEvent(' . $event['Events']['id'] . ')">Mark as done</button>&nbsp;&nbsp;';
                echo '<button class="button" onclick="opportunityEditEvent(' . $event['Events']['id'] . ')">Details</button>&nbsp;&nbsp;';
                echo '<button class="button" onclick="opportunityDeleteEvent(' . $event['Events']['id'] . ')">Delete</button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <h1 style="cursor: pointer;" onclick="loadPage('leads/myopportunities')">My opportunities</h1>
        <table class="stripeme" cellpadding="0" cellspacing="0" width="50%">
            <tr>
                <th class="uppercase">Opportunity</th>
                <th class="uppercase">Company</th>
                <th class="uppercase">Total deal<br />size</th>
                <th class="uppercase">Chance of<br /> sale</th>
                <th class="uppercase">Weighted<br />forecast</th>
            </tr>
            <?php
            $total = 0;
            $weighted = 0;
            foreach ($leads as $lead) {
                $total = $total + $lead['Lead']['amount'];
                $weighted = $weighted + round($lead['Lead']['amount'] * $lead['Lead']['probability'] / 100, 2);
                echo '<tr>';
                echo '<td><a href="#" onclick="return loadPage(\'leads/view/' . $lead['Lead']['id'] . '\');">' . $lead['Lead']['name'] . '</a></td>';
                echo '<td>' . $lead['Company']['name'] . '</td>';
                echo '<td>$ ' . $lead['Lead']['amount'] . '</td>';
                echo '<td>' . $lead['Lead']['probability'] . '%</td>';
                echo '<td>$ ' . round($lead['Lead']['amount'] * $lead['Lead']['probability'] / 100, 2) . '</td>';
                echo '</tr>';
            }
            ?>
            <tfoot>
                <tr class="total">
                    <td colspan="2" class="uppercase alignleft">Total</td>
                    <td colspan="1">$ <?php echo $total; ?></td>
                    <td></td>
                    <td>$ <?php echo $weighted; ?></td>
                </tr>
            </tfoot>
        </table>
        <!-- BEGIN mark as done -->
        <div class="modal" id="markasdone" title="Mark a task as done">
    	Do you confirm this task has been done / not done?
        </div>
        <!-- END mark as done -->
    </body>
</html>
