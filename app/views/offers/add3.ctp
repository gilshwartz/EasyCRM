<div title="Order Ref. <?php echo $orderRef;?>">
    <form action="#" id="offerstep3">
        <table cellpadding="0" cellspacing="0" class="details stripeme">
            <tr>
                <td><strong>Expiring Date</strong></td>
                <td><input name="data[Offer][expiring_date]" type="text" id="enddate" class="editable"/></td>
            </tr>
            <tr>
                <td><strong>Generate Invoice</strong></td>
                <td><input type="checkbox" name="data[Offer][auto_invoice]"/></td>
            </tr>
            <tr>
                <td><strong>Auto send licenses</strong></td>
                <td><input type="checkbox" name="data[Offer][auto_licenses]"/></td>
            </tr>
            <tr>
                <td><strong>Contact</strong></td>
                <td>
                    <input id="infocontact" type="text" class="editable"/>
                    <input type="hidden" name="data[Offer][contact]"/>
                    <script type="text/javascript">
                        $('#infocontact').autocomplete('contacts/autocomplete', {minChars : 3});
                        $("#infocontact").result(function(event, data, formatted) {
                            $("input:hidden[name='data[Offer][contact]']").val(data[1]);
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td><strong>Online Payment ?</strong></td>
                <td><input type="checkbox" onclick="onlinePayment($(this).attr('checked'))"/></td>
            </tr>
            <tr>
                <td><strong>Payment URL</strong></td>
                <td id="url"></td>
            </tr>
        </table>
        <input name="data[Offer][secure_code]" type="hidden" value=""/>
    </form>
    <script type="text/javascript">
        function onlinePayment(online) {
            if (online) {
                var secure = $.sha1(new Date().toGMTString());
                $("input:hidden[name='data[Offer][secure_code]']").val(secure);
                $('#url').html(location.protocol + "//" + location.host + '/store/payment?ref=' + secure);
            } else {
                $("input:hidden[name='data[Offer][secure_code]']").val('');
                $('#url').html('');
            }
        }
        $( "#enddate" ).datepicker({ dateFormat: 'yy-mm-dd' });
    </script>
</div>