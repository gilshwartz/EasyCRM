<form action="#" id="leadjoin">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Enter the first letter of the lead name :</strong></td>
            <td>
                <input id="autocontact" type="text" class="editable" value="<?php echo $lead['Lead']['name'];?>"/>                
            </td>
            <td><img id="reset" src="img/documents/cross.png" width="12px" style="cursor: pointer;"/></td>
        </tr>
    </table>
    <input type="hidden" name="data[LeadsDocument][leads_id]" value="<?php echo $lead['Lead']['id'];?>"/>
    <input type="hidden" name="data[LeadsDocument][documents_id]" value="<?php echo $lead['Document']['id'];?>"/>
</form>
<script type="text/javascript">
    $( '#leadjoin' ).bind('keypress', function(e){
        if ( e.keyCode == 13 ) {
            e.stopPropagation();
            e.preventDefault();
            $( '.ui-dialog-buttonset' ).find( 'button:first' ).click();
        }
    });
    $( '#reset' ).bind('click', function(e){
        $("#autocontact").val('');
        $("input:hidden[name='data[LeadsDocument][leads_id]']").val(0);
    });
    $('#autocontact').autocomplete('leads/lookup', {minChars : 3});
    $("#autocontact").result(function(event, data, formatted) {
        $("input:hidden[name='data[LeadsDocument][leads_id]']").val(data[1]);
    });
</script>