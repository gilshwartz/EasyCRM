<form action="#" id="addfolder">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Folder Name :</strong></td>
            <td><input type="text" name="data[Document][name]"/></td>
        </tr>
    </table>
    <input type="hidden" name="data[Document][path]"/>
    <input type="hidden" name="data[Document][category]"/>
</form>
<script type="text/javascript">
    $( '#addfolder' ).bind('keypress', function(e){
        if ( e.keyCode == 13 ) {
            e.stopPropagation();
            e.preventDefault();
            $( '.ui-dialog-buttonset' ).find( 'button:first' ).click();
        }
    });
</script>