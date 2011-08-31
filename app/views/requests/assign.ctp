<!-- BEGIN assign request modal -->
<div id="requestassign">
    <form action="" >
        <table cellpadding="0" cellspacing="0">
            <?php if ($role_accro != "ptnr"){ ?>
            <tr>
                <td>Group</td>
                <td>
                    <select name="data[Request][partner]">
                        <option value=""></option>
                        <?php
                        foreach ($groups as $key => $value)
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        ?>
                    </select>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td>Account Manager</td>
                <td>
                    <select name="data[Request][user]">
                        <option value=""></option>
                        <?php
                        foreach ($users as $key => $value)
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <input type="hidden" name="data[Request][accpeted]" value="0"/>
    </form>
</div>