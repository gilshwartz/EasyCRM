<?php
if ($emails = unserialize($email['From']['emails']))
    $sendto = $emails[0];
else
    $sendto = $email['From']['emails'];
?>
<div>
    <div style="clear:both;">
        <div>
            <strong><?php echo $email['Email']['subject']; ?></strong>
        </div>
    </div>

    <div style="clear:both;display:block;border-top: solid 1px #eaeaec;margin-top:10px;height:1px;"></div>

    <div style="float: left">
        <div style="padding-bottom:2px;margin-left:3px;width:100%;">
            <span class="darkgreylabel" style="padding-right:10px;">From:</span>
            <?php echo '<a href="#" onclick="opportunityEditContact(' . $email['From']['id'] . ');">' . $email['From']['fullname'] . '</a>'; ?>
        </div>

        <div style="padding-bottom:2px;margin-left:3px;width:100%;">
            <span class="darkgreylabel"style="padding-right:25px;">To:</span>
            <?php
            foreach ($email['To'] as $to)
                echo '<a href="#" onclick="opportunityEditContact(' . $to['id'] . ');">' . $to['fullname'] . '</a>';
            ?>
        </div>

        <?php if (isset($email['Cc']) && !empty($email['Cc'])) { ?>
        <div style="padding-bottom:2px;margin-left:3px;width:100%;">
            <span class="darkgreylabel"style="padding-right:25px;">Cc:</span>
            <?php
                foreach ($email['Cc'] as $cc)
                    echo '<a href="#" onclick="opportunityEditContact(' . $cc['id'] . ');">' . $cc['fullname'] . '</a>';
            ?>
        </div>
        <?php } ?>

        <div style="padding-bottom:4px;margin-left:3px;width:100%;">
            <span class="darkgreylabel" style="padding-right:14px;">Date:</span>
            <?php echo date('r', strtotime($email['Email']['date'])); ?>
        </div>

        <?php if ($email['Email']['lead_id'] != NULL) {?>
        <div style="padding-bottom:2px;margin-left:3px;width:100%;">
            <span class="darkgreylabel"style="padding-right:25px;">Lead:</span>
            <a href="#" onclick="requestShowLead(<?php echo $email['Lead']['id']; ?>);"><?php echo $email['Lead']['name']; ?></a>
        </div>

        <?php } else {?>
        <div style="padding-bottom:2px;margin-left:3px;width:100%;" id="leadselect">
            <span class="darkgreylabel"style="padding-right:25px;">Lead:</span>
            <select onchange="email2lead(<?php echo $email['Email']['id']; ?>, $(this).val());">
                <option></option>
                <?php
                foreach ($leads as $key => $value)
                    echo '<option value="' . $key . '">' . $value . '</option>';
                ?>
            </select>
        </div>
        <?php } ?>
    </div>

    <?php if (isset($email['Document'][0]['name']) && !empty($email['Document'][0]['name'])) {?>
    <div style="float: right">
        <div style="padding-bottom:2px;margin-left:3px;width:100%;">
            <?php
            foreach ($email['Document'] as $document) {
                echo '<a href="#" onclick="download(' . $document['id'] . ');">' . $document['name'] . '</a><br/>';
            }
            ?>
        </div>
    </div>
    <?php } ?>

    <div style="clear:both"></div>

    <div id="EmailBody" style="margin-top:10px;margin-left:4px;padding:10px;font-family:'Arial,Tahoma,Verdana';font-size:10pt;background-color:#fff;border:solid 1px #d5d5d5">
        <?php
        echo nl2br(base64_decode($email['Email']['message']));
        ?>
    </div>

    <div style="clear:both;display:block;border-top: solid 1px #eaeaec;margin-top:10px;height:1px;"></div>

    <div style="float: right; margin-top: 5px;">
        <button class="button" onclick="reply();">Reply</button>
        <button class="button" onclick="emailDelete(<?php echo $email['Email']['id']; ?>); $( '#ajaxmodal' ).dialog('close');">Delete</button>
    </div>
</div>
<script type="text/javascript">
function reply() {
    var to = "<?php echo $sendto; ?>";
    var subject = "RE: <?php echo $email['Email']['subject']; ?>";
    var url = "https://mail.google.com/a/planningforce.com/mail/?view=cm&fs=1&tf=1&to=" + to + "&su=" + subject + "&bcc=administrator%40planningforce.com";
    window . open(url);
}
</script>
<script type="text/javascript">
$(".button").button();
</script>
