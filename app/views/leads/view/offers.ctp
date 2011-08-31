<table cellpadding="0" cellspacing="0" class="stripeme">
    <tr>
        <th>Order#</th>
        <th>Order created on</th>
        <th>VAT excl.</th>
        <th>Status</th>
        <th></th>
    </tr>
    <?php
    foreach ($offers as $offer) {
        if ($offer['Offer']['status'] == 'paid')
            echo '<tr class="bggreen">';
        else if ($offer['Offer']['status'] == 'pending')
            echo '<tr class="bgorange">';
        else
            echo '<tr class="bgred">';
        echo '<td>'.$offer['Offer']['id'].'</td>';
        echo '<td>'.$offer['Offer']['date'].'</td>';
        $amount = 0;
        foreach ($offer['OffersDetail'] as $detail) {
            $amount = $detail['amount'] + $amount;
        }
        echo '<td>'.$offer['Offer']['currency'].' '.$amount.'</td>';
        echo '<td>'.$offer['Offer']['status'].'</td>';
        echo '<td>';
        echo '<button class="button" onclick="offerView(' . $offer['Offer']['id'] . ');">View details</button>&nbsp;&nbsp;';
        if ($offer['Offer']['status'] == 'pending' || $offer['Offer']['status'] == 'creation')
            echo '<button class="button" onclick="offerDelete(' . $offer['Offer']['id'] . ');">Delete</button>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>