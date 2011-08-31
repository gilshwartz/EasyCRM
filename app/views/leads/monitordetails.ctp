<?php
foreach ($users as $key => $value) {
?>
<div>
    <h3 class="darkblue"><?php echo $value;?></h3>
    <table class="stripeme" cellpadding="0" cellspacing="0" width="50%">
        <thead>
            <tr>
                <th class="uppercase">Opportunity</th>
                <th class="uppercase">Company</th>
                <th class="uppercase">Next Step</th>
                <th class="uppercase">Forecast<br />close</th>
                <th class="uppercase">Total deal<br />size</th>
                <th class="uppercase">Chance of<br /> sale</th>
                <th class="uppercase">Weighted<br />forecast</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $total = 0;
                $weighted = 0;
                foreach ($leads as $lead) {
                    if (strtolower($lead['Lead']['user_id']) == $key) {
                        $total = $total + $lead['Lead']['amount'];
                        $weighted = $weighted + round($lead['Lead']['amount'] * $lead['Lead']['probability'] / 100, 2);
                        echo '<tr>';
                            echo '<td><a href="#" onclick="return loadPage(\'leads/view/' . $lead['Lead']['id'] . '\');">' . $lead['Lead']['name'] . '</a></td>';
                            echo '<td>'.$lead['Company']['name'].'</td>';
                            echo '<td>TODO</td>';
                            $date = $lead['Lead']['forecast_close'];
                            if ($date != '')
                                echo '<td>'.date('Y-m-d', strtotime($lead['Lead']['forecast_close'])).'</td>';
                            else
                                echo '<td></td>';
                            echo '<td>$ '.$lead['Lead']['amount'].'</td>';
                            echo '<td>'.$lead['Lead']['probability'].'%</td>';
                            echo '<td>$ '.round($lead['Lead']['amount'] * $lead['Lead']['probability'] / 100, 2).'</td>';
                        echo '</tr>';
                    }
                }
            ?>
            <tr class="total">
                <td colspan="4" class="uppercase alignleft">Total</td>
                <td colspan="1">$ <?php echo $total;?></td>
                <td></td>
                <td>$ <?php echo $weighted;?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
}
?>