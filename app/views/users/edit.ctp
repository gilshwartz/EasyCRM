<form action="#" id="user2edit">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>Role</strong></td>
            <td>
                <select name="data[User][role_id]" class="select">
                    <?php
                    foreach ($roles as $key => $value)
                        if ($user['User']['role_id'] == $key)
                            echo '<option value='.$key.' selected>'.$value.'</option>';
                        else
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
                        if ($user['User']['group_id'] == $key)
                            echo '<option value='.$key.' selected>'.$value.'</option>';
                        else
                            echo '<option value='.$key.'>'.$value.'</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Username</strong></td>
            <td><input type="text" name="data[User][username]" value="<?php echo $user['User']['username'];?>" class="editable"/></td>
        </tr>       
        <tr>
            <td><strong>Firstname</strong></td>
            <td>
                <input type="text" name="data[User][firstname]" value="<?php echo $user['User']['firstname'];?>" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>Lastname</strong></td>
            <td>
                <input type="text" name="data[User][lastname]" value="<?php echo $user['User']['lastname'];?>" class="editable"/>
            </td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>
                <input type="text" name="data[User][email]" value="<?php echo $user['User']['email'];?>" class="editable"/>
            </td>
        </tr>
        <tr>
            <td><strong>Active</strong></td>
            <td>
                <?php
                if($user['User']['active'])
                    echo '<input type="checkbox" name="data[User][active]" checked />';
                else
                    echo '<input type="checkbox" name="data[User][active]"/>';
                ?>
            </td>
        </tr>
        <tr>
            <td><strong>ICS Calendar Link</strong></td>
            <td>
                <a href="services/calendars/<?php echo sha1($user['User']['id']);?>.ics" target="_blank">My Calendar</a>
            </td>
        </tr>
    </table>
</form>