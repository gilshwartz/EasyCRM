<form action="#" id="group2edit">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Parent</strong></td>
            <td>
                <select name="data[Group][parent_id]" class="select">
                    <?php
                    foreach ($parentGroups as $key => $value)
                        if ($group['Group']['parent_id'] == $key)
                            echo '<option value='.$key.' selected>'.$value.'</option>';
                        else
                            echo '<option value='.$key.'>'.$value.'</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><input type="text" name="data[Group][name]" value="<?php echo $group['Group']['name'];?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><textarea name="data[Group][description]" class="editable"><?php echo $group['Group']['description'];?></textarea></td>
        </tr>
    </table>
</form>