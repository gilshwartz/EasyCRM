<form action="#" id="company2edit">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Name</strong></td>
            <td><input type="text" name="data[Company][name]" value="<?php echo $company['Company']['name']; ?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td>
                <textarea name="data[Company][description]" class="editable"><?php echo $company['Company']['description']; ?></textarea>
            </td>
        </tr>

        <tr>
            <td><strong>Address</strong></td>
            <td>
                <textarea name="data[Company][address]" class="editable"><?php echo $company['Company']['address']; ?></textarea>
            </td>
        </tr>

        <tr>
            <td><strong>Country</strong></td>
            <td>
                <select name="data[Company][country_id]">
                    <?php
                    foreach ($countries as $key => $value)
                        if ($key == $company['Company']['country_id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td><strong>Phone</strong></td>
            <td>
                <input type="text" name="data[Company][phone]" value="<?php echo $company['Company']['phone']; ?>" class="editable"/>
            </td>
        </tr>
        
        <tr>
            <td><strong>Website</strong></td>
            <td>
                <input type="text" name="data[Company][url]" value="<?php echo $company['Company']['url']; ?>" class="editable"/>
            </td>
        </tr>
        
        <tr>
            <td><strong>Email</strong></td>
            <td>
                <input type="text" name="data[Company][email]" value="<?php echo $company['Company']['email']; ?>" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>VAT</strong></td>
            <td>
                <input type="text" name="data[Company][vat]" value="<?php echo $company['Company']['vat']; ?>" class="editable" id="numvat"/>
                <img src="img/icons/verify.png" id="verify" alt="" onclick="checkVat($('#numvat').val());" style="cursor: pointer"/>
            </td>
        </tr>
        <?php if (!$is_partner) { ?>
        <tr>
            <td><strong>Partner?</strong></td>
            <td>
                <?php 
                if ($company['Company']['is_partner']) { 
                    echo '<input type="checkbox" name="data[Company][is_partner]" checked/>';
                } else {
                    echo '<input type="checkbox" name="data[Company][is_partner]" />';
                }
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</form>