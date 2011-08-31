<form action="#" id="company2add">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Name</strong></td>
            <td><input type="text" name="data[Company][name]" value="" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td>
                <textarea name="data[Company][description]" class="editable"></textarea>
            </td>
        </tr>

        <tr>
            <td><strong>Address</strong></td>
            <td>
                <textarea name="data[Company][address]" class="editable"></textarea>
            </td>
        </tr>

        <tr>
            <td><strong>Country</strong></td>
            <td>
                <select name="data[Company][country_id]">
                    <?php
                    foreach ($countries as $key => $value)
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td><strong>Phone</strong></td>
            <td>
                <input type="text" name="data[Company][phone]" value="" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>Website</strong></td>
            <td>
                <input type="text" name="data[Company][url]" value="" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>
                <input type="text" name="data[Company][email]" value="" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>VAT</strong></td>
            <td>
                <input type="text" name="data[Company][vat]" value="" class="editable" id="numvat"/>
                <img src="img/icons/verify.png" id="verify" alt="" onclick="checkVat($('#numvat').val());" style="cursor: pointer"/>
            </td>
        </tr>
        <?php if (!$is_partner) { ?>
        <tr>
            <td><strong>Partner?</strong></td>
            <td>
                <input type="checkbox" name="data[Company][is_partner]" value="" />
            </td>
        </tr>
        <?php } ?>
    </table>
</form>