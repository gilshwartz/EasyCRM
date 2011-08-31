<!-- BEGIN request view details modal -->
<div id="requestActions">
    <span class="related">Possible actions on this request</span>
    <?php
    if ($request['User']['id'] == $session->read('Auth.User.id'))
            echo '<button class="button" onclick="requestAccept('.$request['Request']['id'].');">Accept this request</button>';
    ?>    
    <?php if ($role_accro != "ptnr"){ ?>
    <button class="button" onclick="requestDelete(<?php echo $request['Request']['id']; ?>);">Delete request</button>
    <button class="button" onclick="contactDelete(<?php echo $request['Contact']['id']; ?>);">Delete contact</button>
    <button class="button" onclick="requestUpdateStatus(<?php echo $request['Request']['id']; ?>);">To Check</button>
    <?php } ?>
    <button class="button" onclick="requestAssign(<?php echo $request['Request']['id']; ?>);">Assign</button>
</div>
<div id="requestDetails">
    <span class="related">Details</span>
    <table class="simpledata">
        <tr>
            <td>
                <table width="300px">
                    <tr>
                        <td><strong>Request status</strong></td>
                        <td>
                            <select name="status" onchange="requestUpdateStatus(<?php echo $request['Request']['id']; ?>, $(this).val());">
                                <option <?php if (strtolower($request['Request']['status']) == "open")
    echo "SELECTED" ?>>Open</option>
                                <option <?php if (strtolower($request['Request']['status']) == "pending")
                                    echo "SELECTED" ?>>Pending</option>
                                <option <?php if (strtolower($request['Request']['status']) == "working")
                                    echo "SELECTED" ?>>Working</option>
                                <option <?php if (strtolower($request['Request']['status']) == "close")
                                    echo "SELECTED" ?>>Close</option>
                                <option <?php if (strtolower($request['Request']['status']) == "tocheck")
                                    echo "SELECTED" ?>>To Check</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="alt">
                        <td><strong>Date</strong></td>
                        <td><?php echo $request['Request']['dateIn']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Company</strong></td>
                        <td><?php echo $request['Contact']['company_name']; ?></td>
                    </tr>
                    <tr class="alt">
                        <td><strong>First name</strong></td>
                        <td><?php echo $request['Contact']['firstname']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last name</strong></td>
                        <td><?php echo $request['Contact']['lastname']; ?></td>
                    </tr>
                    <tr class="alt">
                        <td><strong>Email</strong></td>
                        <td>
                            <?php
                            if ($emails = unserialize($request['Contact']['emails']))
                                foreach ($emails as $email)
                                    echo $email . '<br/>';
                            else
                                echo $request['Contact']['emails'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td>
                            <?php
                            if ($emails = unserialize($request['Contact']['phones']))
                                foreach ($emails as $email)
                                    echo $email . '<br/>';
                            else
                                echo $request['Contact']['phones'];
                            ?>
                        </td>
                    </tr>
                    <tr class="alt">
                        <td><strong>Country</strong></td>
                        <td><?php echo $countries[$request['Contact']['country']]; ?></td>
                    </tr>
                </table>
            </td>

            <td>
                <table>
                    <tr>
                        <td colspan="2"><strong>[Request details]</strong></td>
                    </tr>
                    <?php
                    if (isset($request['RequestContact']['subject']) && $request['RequestContact']['subject'] != "") {
                        echo '<tr>';
                        echo '<td><strong>Subject</strong></td>';
                        echo '<td>' . $request['RequestContact']['subject'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>Message</strong></td>';
                        echo '<td>' . $request['RequestContact']['message'] . '</td>';
                        echo '</tr>';
                    } else if (isset($request['RequestTrial']['product']) && $request['RequestTrial']['product'] != "") {
                        echo '<tr>';
                        echo '<td><strong>Product</strong></td>';
                        echo '<td>' . $request['RequestTrial']['product'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>Message</strong></td>';
                        echo '<td>' . $request['RequestTrial']['message'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>Installation ID</strong></td>';
                        echo '<td>' . $request['RequestTrial']['installation_id'] . '</td>';
                        echo '</tr>';
                    } else if (isset($request['RequestQuote']['product']) && $request['RequestQuote']['product'] != "") {
                        echo '<tr>';
                        echo '<td><strong>Product</strong></td>';
                        echo '<td>' . $request['RequestQuote']['product'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>License Usage</strong></td>';
                        echo '<td>' . $request['RequestQuote']['lciense_usage'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>Amount</strong></td>';
                        echo '<td>' . $request['RequestQuote']['amount'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>Resources</strong></td>';
                        echo '<td>' . $request['RequestQuote']['resources'] . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td><strong>Projects</strong></td>';
                        echo '<td>' . $request['RequestQuote']['projects'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
    </table>
</td>
</tr>
</table>

<div id="requestOpportunity">
    <span class="related">Possible actions on this request</span>
    <button class="button" onclick="requestAddEvent();">Add an event</button>
    <?php if (!isset($linkedlead) || empty($linkedlead)) { ?>
        <button class="button marginright15" onclick="requestCreateOpportunity(<?php echo $request['Request']['id'] . ', ' . $request['Contact']['id']; ?>);">Create opportunity</button>
        <select onchange="requestLink2Opportunity(this);">
            <option>[Add to existing opportunity]</option>
            <?php
            foreach ($leads as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            }
            ?>
        </select>
        <?php
    } else {
        echo '<button class="button marginright15" onclick="requestShowLead(' . $linkedlead['LeadsRequest']['leads_id'] . ');">Show related opportunity</button>';
    }
    ?>
</div>

<span class="related">Related notes</span>
<?php
foreach ($request['EventsRequest'] as $event) {
    echo '<p class="noteauthor">' . $events[$event['events_id']]['type'] . ' - ' . date('Y-m-d', strtotime($events[$event['events_id']]['date'])) . ' - ' . $events[$event['events_id']]['subject'] . '</p>';
    echo '<p class="notedetail">' . $events[$event['events_id']]['message'] . '</p>';
}
?>
<script>
    $(".button").button();
</script>
</div>
<!-- END request view details modal -->
