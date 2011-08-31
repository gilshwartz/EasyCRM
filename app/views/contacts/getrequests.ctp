<table cellpadding="0" cellspacing="0" class="details stripeme">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($requests as $request) {
            echo '<tr>';
            echo '<td><a href="#" onclick="requestDetails('. $request['Request']['id'] . ');">' . $request['Request']['type'] . '</a></td>';
            echo '<td>' . $request['Request']['status'] . '</td>';
            echo '<td>' . $request['Request']['dateIn'] . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>