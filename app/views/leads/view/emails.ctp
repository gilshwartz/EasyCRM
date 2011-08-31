<table cellpadding="0" cellspacing="0" class="stripeme">
    <tr>
        <th>Date</th>
            <th>From</th>
            <th>To</th>
            <th>Subject</th>
            <th></th>
    </tr>
    <?php
        foreach($emails as $email) {
            echo '<tr>';
                echo '<td>'.$email['Email']['date'].'</td>';
                echo '<td>'.$email['From']['fullname'].'</td>';
                echo '<td>'.$email['To'][0]['fullname'].'</td>';
                echo '<td>'.$email['Email']['subject'].'</td>';
                echo '<td>';
                    echo '<button class="button" onclick="viewEmail('.$email['Email']['id'].')">View</button>&nbsp;';
                    echo '<button class="button" onclick="emailDelete('.$email['Email']['id'].')">Delete</button>';
                echo '</td>';
            echo '</tr>';
        }
        ?>
</table>