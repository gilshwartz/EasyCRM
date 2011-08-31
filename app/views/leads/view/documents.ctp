<table cellpadding="0" cellspacing="0" class="stripeme">
    <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($documents as $doc) {
            echo '<tr>';
                echo '<td>'.$doc['Document']['name'].'</td>';
                echo '<td>'.$doc['Document']['date'].'</td>';
                echo '<td>';
                    echo '<a href="documents/view/'.$doc['Document']['id'].'" class="button" >Download</a>';
                echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>