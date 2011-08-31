<?php
$leadSources = array('Website', 'Google', 'Prospection');
$leadStatus = array('Suspect', 'Prospect', 'Opportunity', 'Avangate', 'Client', 'Lost');
$probability = array('0', '25', '50', '75', '90', '100');
?>
<script>
    var id = <?php echo $lead['Lead']['id']; ?>;
</script>
<div style="float: left;margin-bottom:10px;">
    <h1><?php echo ucfirst($lead['Lead']['status']); ?>: <span class="gray"><?php echo $lead['Lead']['name']; ?></span></h1>
    <div class="buttons">
        <button class="button" onclick="opportunitySave();">Save changes</button>
        <button class="button" onclick="opportunityDelete();">Delete this opportunity</button>
    </div>
</div>        
<div style="float: right;margin-bottom:10px;">
    <h1>Email ID : <span class="gray"><?php echo $token; ?></span></h1>
</div>
<?php if ($partner_company['Company']['is_partner']) { ?>
    <div style="float: right;margin-bottom:10px;margin-right: 25px;">
        <h1><span class="gray">Partner</span></h1>
    </div>
<?php } ?>
<div class="clearboth"></div>
<form id="leadform" action="" >
    <table class="opportunity" cellpadding="0" cellspacing="0" width="100%" >
        <tr>
            <td class="labelfield">Opportunity name</td>
            <td><input name="data[Lead][name]" type="text" value="<?php echo $lead['Lead']['name']; ?>" class="editable" onchange="$(this).removeClass('ui-state-error');"/></td>
            <td class="labelfield">Amount</td>
            <td>USD <input name="data[Lead][amount]" type="text" value="<?php echo $lead['Lead']['amount']; ?>" class="editable" onchange="calculWeighted();"/></td>
        </tr>
        <tr>
            <td class="labelfield">Company name</td>
            <td>
                <select name="data[Lead][company_id]" class="select">
                    <option value=""></option>
                    <?php
                    foreach ($companies as $key => $value)
                        if ($key == $lead['Company']['id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
        <center>                            
            <small><a href="#" onclick="opportunityCreateCompany();return false;">Create a company</a></small>
            <small><a href="#" onclick="companyEdit();return false;">Edit company</a></small>
        </center>
        </td>
        <td class="labelfield">Probability</td>
        <td>
            <select name="data[Lead][probability]" class="select" onchange="calculWeighted();">
                <?php
                foreach ($probability as $value)
                    if ($value == $lead['Lead']['probability'])
                        echo '<option value="' . $value . '" SELECTED>' . $value . ' %</option>';
                    else
                        echo '<option value="' . $value . '">' . $value . ' %</option>';
                ?>
            </select>
        </td>
        </tr>
        <tr>
            <td class="labelfield">Lead source</td>
            <td>
                <select name="data[Lead][source]" class="select">
                    <option value=""></option>
                    <?php
                    foreach ($leadSources as $value)
                        if ($value == $lead['Lead']['source'])
                            echo '<option SELECTED>' . $value . '</option>';
                        else
                            echo '<option>' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td class="labelfield">Weighted Amount</td>
            <td>
                <div id="weighted"></div>
                <script>
                    function calculWeighted() {
                        var amount = $("input[name='data[Lead][amount]']").val();
                        var probability = $("select[name='data[Lead][probability]']").val();
                        var weighted = amount * probability / 100;
                        $('#weighted').text('USD ' + weighted);
                    }
                    calculWeighted();
                </script>
            </td>
        </tr>
        <tr>
            <td class="labelfield">Assigned to</td>
            <td>
                <select name="data[Lead][user_id]" class="select">
                    <option value=""></option>
                    <?php
                    foreach ($users as $key => $value)
                        if ($key == $lead['User']['id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td class="labelfield">Forecast Close</td>
            <td>
                <input name="data[Lead][forecast_close]" type="text" id="date" class="editable" value="
                <?php
                if ($lead['Lead']['forecast_close'] != '')
                    echo date('Y-m-d', strtotime($lead['Lead']['forecast_close']));
                ?>"/>
            </td>
        </tr>
        <tr>
            <td class="labelfield">Lead Status</td>
            <td>
                <select name="data[Lead][status]" class="select">
                    <?php
                    foreach ($leadStatus as $value)
                        if (strtolower($value) == strtolower($lead['Lead']['status']))
                            echo '<option SELECTED>' . $value . '</option>';
                        else
                            echo '<option>' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td class="labelfield">Actions</td>
            <td><input type="button" class="button" onclick="opportunityHistory();" value="Show History"/></td>
        </tr>
        <tr>
            <td class="labelfield">Description</td>
            <td colspan="3">
                <div class="opportunity_description" onclick="leadDescription();"><?php echo html_entity_decode($lead['Lead']['description']); ?></div>
                <input type="hidden" name="data[Lead][description]" value="<?php echo $lead['Lead']['description']; ?>" />
            </td>
        </tr>
    </table>

    <input type="hidden" name ="data[Lead][msg]"/>
</form>

<div class="separator">
    <h2 class="showdetails" onclick="opportunityShowDetails($('#contactsdetails'));">Contacts</h2>
    <?php if (!$is_partner) { ?>
        <button class="action" onclick="opportunityAddContact()">Assign a new contact</button>
    <?php } ?>
    <button class="action" onclick="opportunityCreateContact()">Create a new contact</button>
    <div id="contactsdetails"></div>
    <div class="clearboth"></div>
</div>
<?php if (!$is_partner) { ?>
    <div class="separator">
        <h2 class="showdetails" onclick="opportunityShowDetails($('#emailsdetails'));">Emails</h2>
        <button class="action" onclick="sendMail('<?php echo $token; ?>');">Send Email</button>
        <div id="emailsdetails"></div>
        <div class="clearboth"></div>
    </div>
<?php } ?>
<div class="separator">
    <h2 class="showdetails" onclick="opportunityShowDetails($('#requestsdetails'));">Requests</h2>
    <div id="requestsdetails"></div>
    <div class="clearboth"></div>
</div>

<?php if (!$is_partner) { ?>
    <div class="separator">
        <h2 class="showdetails" onclick="opportunityShowDetails($('#ordersdetails'));">Orders and invoices</h2>
        <button class="action" onclick="offerCreate(<?php echo $lead['Lead']['id']; ?>);">Create new order</button>
        <div id="ordersdetails"></div>
        <div class="clearboth"></div>
    </div>
<?php } ?>
<div class="separator">
    <h2 class="showdetails" onclick="opportunityShowDetails($('#notesdetails'));">Notes and reminders</h2>
    <button class="action" onclick="opportunityAddEvent()">Add an event</button>
    <div id="notesdetails"></div>
    <div class="clearboth"></div>
</div>

<div class="separator">
    <h2 class="showdetails" onclick="opportunityShowDetails($('#documentsdetails'));">Documents</h2>
    <div id="documentsdetails"></div>
    <div class="clearboth"></div>
</div>


<!-- Edit contact -->
<div id="editcontact" title="Edit contact details"></div>
<!-- -->

<!-- create contact -->
<div id="createcontact" title="Create a new contact"></div>
<!-- -->

<!-- Assign a contact -->
<div id="assigncontact" title="Assign a contact to this opportunity">
    <center>
        <p>Enter the first letter of your contact<br /><br /></p>
        <input id="autocontact" type="text" class="editable"/>
        <input type="hidden" name="data[Contact][id]"/>
    </center>
</div>
<!-- -->        

<!-- Create a note -->
<div id="createreminder" title="Add a reminder"></div>
<!-- -->

<!-- History Box -->
<div id="history" title="History" style="display:none;"></div>
<!-- -->

<!-- History MsgBox -->
<div id="msg4history" title="Message" style="display:none;"><br />
    <form id="msg4history" action="">
        Save Message (optional) : <input type="text" class="editable" name="data[Lead][msg]"/>
    </form>
    <div id="errormsg4history"style="float:left;margin-top: 10px;color:red"></div>
</div>
<!-- -->

<script language="javascript">
    $(function() {
        $('select.select').selectmenu({maxHeight : 250});
        $( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $('#autocontact').autocomplete('contacts/autocomplete', {minChars : 3});
        $("#autocontact").result(function(event, data, formatted) {
            $("input:hidden[name='data[Contact][id]']").val(data[1]);
        });
        $('#leadform').change(function() {
            formModified = true;
        });
    });
</script>

