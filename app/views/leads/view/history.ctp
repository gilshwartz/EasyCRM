<table class="stripeme" cellpadding="0" cellspacing="0" width="30%">
    <tr>
        <th>Date</th>
        <th>User</th>
        <th>Log</th>
    </tr>
    <?php
    $hist = unserialize($history);
    foreach ($hist as $key => $value) {
        echo '<tr>';
        echo '<td>' . date('Y-m-d H:i:s', $key) . '</td>';
        echo '<td>' . $value['user'] . '</td>';
        echo '<td>' . $value['message'] . '</td>';
        echo '</tr>';
    }
    ?>
</table>