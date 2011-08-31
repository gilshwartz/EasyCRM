<table cellpadding="0" cellspacing="0" class="details stripeme">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Forecast Close</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($leads as $lead) {
            echo '<tr>';
            echo '<td><a href="#" onclick="goToLead('. $lead['Leads']['id'] . ');">' . $lead['Leads']['name'] . '</a></td>';
            echo '<td>' . $lead['Leads']['status'] . '</td>';
            echo '<td>' . $lead['Leads']['forecast_close'] . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>