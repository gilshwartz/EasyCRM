<table cellpadding="0" cellspacing="0" class="stripeme">
    <tr>
        <th>Name</th>
        <th>Position</th>
        <th>Role</th>
        <th>Email</th>
        <th>Phone</th>
        <th></th>
    </tr>
    <?php
    foreach ($contacts as $contact) {
        if ($emails = unserialize($contact['Contacts']['emails']))
            $email = $emails[0];
        else
            $email = $contact['Contacts']['emails'];
        echo '<tr>';
        echo '<td><a href="#" onclick="opportunityEditContact(' . $contact['Contacts']['id'] . ')">' . $contact['Contacts']['firstname'] . ' ' . $contact['Contacts']['lastname'] . '</a></td>';
        echo '<td>' . $contact['Contacts']['position'] . '</td>';
        echo '<td>' . $contact['Contacts']['role'] . '</td>';
        echo '<td>' . $email . '</td>';
        echo '<td>';
        if ($phones = unserialize($contact['Contacts']['phones']))
            echo $phones[0];
        else
            echo $contact['Contacts']['phones'];
        echo '</td>';
        echo '<td>';
        echo '<button class="button" onclick="opportunityEditContact(' . $contact['Contacts']['id'] . ')">Edit</button>&nbsp;&nbsp;';
        if (!$is_partner)
            echo '<button class="button" onclick="sendMail(\'' . $token . '\', \'' . $email . '\');">Send Email</button>&nbsp;&nbsp;';
        
        echo '<button class="button" onclick="opportunityRemoveContact(' . $contact['LeadsContact']['id'] . ')">Remove</button>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>