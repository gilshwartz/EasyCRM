<form action="#" id="group2add">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Parent</strong></td>
            <td>
                <select name="data[Group][parent_id]" class="select">
                    <?php
                    foreach ($parentGroups as $key => $value)
                        echo '<option value='.$key.'>'.$value.'</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><input type="text" name="data[Group][name]" value="" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><textarea name="data[Group][description]" class="editable"></textarea></td>
        </tr>
    </table>
</form>