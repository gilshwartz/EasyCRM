<table cellpadding="0" cellspacing="0" class="stripeme">
    <tr>
        <th>Statut</th>
        <th>Assigned to</th>
        <th>Type</th>
        <th>Date</th>
        <th></th>
    </tr>
    <?php
    foreach ($requests as $request) {
        echo '<tr>';
        echo '<td>'.$request['Requests']['status'].'</td>';
        if (isset($users[$request['Requests']['user']]))
            echo '<td>'.$users[$request['Requests']['user']].'</td>';
        else
            echo '<td>N/A</td>';
        echo '<td>'.$request['Requests']['type'].'</td>';
        echo '<td>' . date('Y-m-d', strtotime($request['Requests']['dateIn'])) . '</td>';
        echo '<td>';
            echo '<button class="button" onclick="requestDetails(' . $request['Requests']['id'] . ');">View details</button>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>