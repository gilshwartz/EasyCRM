<div title="Order Ref. <?php echo $orderRef; ?>">
    <form action="#" id="offerstep1">
        <table cellpadding="0" cellspacing="0" class="details stripeme">
            <tr>
                <td><strong>Company</strong></td>
                <td><input type="text" name="data[Offer][billing_company]" value="" class="editable" onchange="$(this).removeClass('ui-state-error');"/></td>
            </tr>
            <tr>
                <td><strong>Name</strong></td>
                <td><input type="text" name="data[Offer][billing_name]" value="" class="editable" onchange="$(this).removeClass('ui-state-error');"/></td>
            </tr>
            <tr>
                <td><strong>Address</strong></td>
                <td><textarea name="data[Offer][billing_address]" class="editable" onchange="$(this).removeClass('ui-state-error');"></textarea></td>
            </tr>
            <tr>
                <td><strong>Country</strong></td>
                <td>
                    <select name="data[Offer][billing_country_id]" class="select" onchange="$(this).removeClass('ui-state-error');">
                        <?php
                        foreach ($countries as $key => $value)
                            echo '<option value=' . $key . '>' . $value . '</option>';
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>VAT</strong></td>
                <td>
                    <input type="text" name="data[Offer][billing_vat]" value="" class="editable" id="numvat"/>
                    <img src="img/icons/verify.png" id="verify" alt="" onclick="checkVat($('#numvat').val());" style="cursor: pointer"/>
                </td>
            </tr>
            <tr>
                <td><strong>Professionnal</strong></td>
                <td><input type="checkbox" name="data[Offer][is_pro]" checked/></td>
            </tr>
            <tr>
                <td><strong></strong></td>
                <td></td>
            </tr>
        </table>
    </form>
    <script type="text/javascript">
        function validate() {
            var error = 0;
            if ($('input[name="data[Offer][billing_company]"]').val() == "") {
                $('input[name="data[Offer][billing_company]"]').addClass('ui-state-error');
                error++;
            }
            if ($('input[name="data[Offer][billing_name]"]').val() == "") {
                $('input[name="data[Offer][billing_name]"]').addClass('ui-state-error');
                error++;
            }
            if ($('textarea[name="data[Offer][billing_address]"]').val() == "") {
                $('textarea[name="data[Offer][billing_address]"]').addClass('ui-state-error');
                error++;
            }
            if ($('select[name="data[Offer][billing_country_id]"]').val() == "1") {
                $('select[name="data[Offer][billing_country_id]"]').addClass('ui-state-error');
                error++;
            }
            if (error == 0) {
                return true;
            } else{
                $('#offerstep1 table tr:last td:first').html('<strong>Error</strong>').addClass('ui-state-error-text');
                $('#offerstep1 table tr:last td:last').html('All field are requiered').addClass('ui-state-error-text');
                return false;
            }
        }
    </script>
</div>