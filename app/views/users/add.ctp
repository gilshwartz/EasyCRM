<form action="#" id="user2add">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Role</strong></td>
            <td>
                <select name="data[User][role_id]" class="select">
                    <?php
                    foreach ($roles as $key => $value)
                        echo '<option value='.$key.'>'.$value.'</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Group</strong></td>
            <td>
                <select name="data[User][group_id]" class="select">
                    <?php
                    foreach ($groups as $key => $value)
                        echo '<option value='.$key.'>'.$value.'</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Username</strong></td>
            <td><input type="text" name="data[User][username]" value="" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Password</strong></td>
            <td><input type="text" name="data[User][password]" value="" class="editable"/><img src="img/icons/key.png" alt="" onclick="generateUserPassword()"/></td>
        </tr>
        <tr>
            <td><strong>Firstname</strong></td>
            <td>
                <input type="text" name="data[User][firstname]" value="" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>Lastname</strong></td>
            <td>
                <input type="text" name="data[User][lastname]" value="" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>
                <input type="text" name="data[User][email]" value="" class="editable"/>
            </td>
        </tr>
    </table>
</form>