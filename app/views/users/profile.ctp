<form action="#" id="user2edit">
    <table cellpadding="0" cellspacing="0" class="details stripeme">              
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
                <input type="hidden" name="data[User][active]" value="-1" class="editable"/>
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