<table cellpadding="0" cellspacing="0" class="details stripeme">
    <tr>
        <td><strong>Company</strong></td>
        <td><?php echo $offer['Offer']['billing_company']; ?></td>
        <td><strong>Name</strong></td>
        <td><?php echo $offer['Offer']['billing_name']; ?></td>
    </tr>
    <tr>
        <td><strong>Address</strong></td>
        <td><?php echo $offer['Offer']['billing_address']; ?></td>
        <td><strong>Country</strong></td>
        <td><?php echo $offer['Country']['name']; ?></td>
    </tr>

    <tr>
        <td><strong>VAT</strong></td>
        <td><?php echo $offer['Offer']['billing_vat']; ?></td>
        <td><strong>Professionnal</strong></td>
        <td><?php echo $offer['Offer']['is_pro']; ?></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" class="details stripeme">
    <thead>
        <tr>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Discount (%)</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $subtotal = 0;
        $vat = 0;
        foreach ($offer['OffersDetail'] as $detail) {
            $subtotal = $subtotal + $detail['amount'];
        ?>
            <tr>
                <td><?php echo $detail['product_name']; ?></td>
                <td><?php echo $offer['Offer']['currency'].' '.$detail['unit_price']; ?></td>
                <td><?php echo $detail['quantity']; ?></td>
                <td><?php echo $detail['discount']; ?></td>
                <td><?php echo $offer['Offer']['currency'].' '.$detail['amount']; ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3"></th>
            <th>Total excl. VAT</th>
            <th><?php echo $offer['Offer']['currency'].' '.$subtotal; ?></th>
        </tr>
        <tr>
            <th colspan="3"></th>
            <th>VAT amount</th>
            <th>
                <?php
                if ($has_vat)
                    $vat = ($subtotal * 0.21);
                echo $offer['Offer']['currency'].' '.$vat;
                ?>
            </th>
        </tr>
        <tr>
            <th colspan="3"></th>
            <th>Total incl. VAT</th>
            <th><?php echo $offer['Offer']['currency'].' '.($subtotal + $vat); ?></th>
        </tr>
    </tfoot>
</table>
<?php
if ($offer['Offer']['status'] == 'pending') {
    if (!empty($_SERVER['HTTPS']))
        $url = 'https://'.$_SERVER['SERVER_NAME'].'/store/payment?ref='.$offer['Offer']['secure_code'];
    else
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/store/payment?ref='.$offer['Offer']['secure_code'];
    
    echo '<label>Payment URL :</label>';
    echo '<a href="'.$url.'" target="blank">'.$url.'</a>';
} elseif ($offer['Offer']['status'] == 'paid') {
    if (!empty($_SERVER['HTTPS']))
        $url = 'https://'.$_SERVER['SERVER_NAME'].'/store/invoice/'.$offer['Offer']['secure_code'];
    else
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/store/invoice/'.$offer['Offer']['secure_code'];

    echo '<label>View Invoice :</label>';
    echo '<a href="'.$url.'" target="blank">'.$url.'</a>';
    echo '<a href="'.$url.'.pdf" target="blank" style="float:right;">Download PDF</a>';
}
?>
