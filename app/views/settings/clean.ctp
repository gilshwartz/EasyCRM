<?php
    echo $this->element('submenu/settings');
?>
<script type="text/javascript">
setActiveSubmenu("snav7");
</script>
<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">Cleaning :</h1>
</div>

<table cellpadding="0" cellspacing="0" class="details stripeme">
    <thead>
        <tr>
            <th>Type</th>
            <th>Emails</th>
            <th></th>
        </tr>
    </thead>
    <tr>
        <td><strong>Email bounces</strong></td>
        <td>
            <form action="#" id="bounces">
                <textarea name="emails" cols="80" rows="5"></textarea>
            </form>
        </td>
        <td><button class="button" onclick="cleanBounces();">Delete</button></td>
    </tr>
    <tr>
        <td><strong>Unsubscribe from newsletter</strong></td>
        <td>
            <form action="#" id="newsletter">
                <textarea name="emails" cols="80" rows="5"></textarea>
            </form>
        </td>
        <td><button class="button" onclick="cleanNewsletter();">Unsubscribe</button></td>
    </tr>
    <tr>
        <td><strong>Unsubscribe from community newsletter</strong></td>
        <td>
            <form action="#" id="community">
                <textarea name="emails" cols="80" rows="5"></textarea>
            </form>
        </td>
        <td><button class="button" onclick="cleanCommunity();">Unsubscribe</button></td>
    </tr>
</table>
