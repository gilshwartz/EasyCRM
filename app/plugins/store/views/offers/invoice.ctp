<?php
$url = $_SERVER['REQUEST_URI'].'.pdf';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>Invoice Preview - PlanningForce</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../css/invoice.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="../css/invoice_print.css" media="print"/>
        <title>Invoice</title>
    </head>
    <body>
        <div id="container">
            <div id="left_big" class="clearboth">
                <img src="../img/planningforce.png" class="logo"/>
                <div class="quoteinfo">

                    <strong>ISC - PlanningForce</strong><br/>
                    Chauss&eacute;e de Nivelles, 121/2<br/>
                    7181 Arquennes - Belgium<br/>
                    <strong>Phone:</strong> +32 67 550 224<br/>
                    <strong>Email:</strong> <a href="mailto:sales@planningforce.com">sales@planningforce.com</a>
                    <br  /><strong>V.A.T:</strong> BE 0473.176.292
                </div>

                <h1 class="clearboth">Invoice <?php echo $offer['Offer']['invoice_id']; ?></h1>

                <div class="quoteaddress">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" class="tableinside">                                    
                                    <tr>
                                        <td>
                                            <strong>Order Reference</strong>
                                        </td>
                                        <td>
                                            <?php echo $offer['Offer']['order_id']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Company</strong>
                                        </td>
                                        <td>
                                            <?php echo $offer['Offer']['billing_company']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Name</strong>
                                        </td>
                                        <td>
                                            <?php echo $offer['Offer']['billing_name']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>VAT number</strong>
                                        </td>
                                        <td>
                                            <?php echo $offer['Offer']['billing_vat']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table cellspacing="0" cellpadding="0" class="tableinside">
                                    <tr>
                                        <td>
                                            <strong>Date of payment</strong>
                                        </td>
                                        <td>
                                            <?php echo $offer['Offer']['date_paid']; ?>
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <td>
                                            <strong>Address</strong>
                                        </td>
                                        <td>
                                            <?php echo nl2br($offer['Offer']['billing_address']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Country</strong>
                                        </td>
                                        <td>
                                            <?php echo $offer['Country']['name']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
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
                                                    <td><?php echo $offer['Offer']['currency'] . ' ' . $detail['unit_price']; ?></td>
                                                    <td><?php echo $detail['quantity']; ?></td>
                                                    <td><?php echo $detail['discount']; ?></td>
                                                    <td><?php echo $offer['Offer']['currency'] . ' ' . $detail['amount']; ?></td>
                                                </tr>
                        <?php
                                            }
                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th>Total excl. VAT</th>
                                                <th><?php echo $offer['Offer']['currency'] . ' ' . $subtotal; ?></th>
                                            </tr>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th>VAT amount</th>
                                                <th>
                                <?php
                                            if ($has_vat)
                                                $vat = ($subtotal * 0.21);
                                            echo $offer['Offer']['currency'] . ' ' . $vat;
                                ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th>Total incl. VAT</th>
                                        <th><?php echo $offer['Offer']['currency'] . ' ' . ($subtotal + $vat); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <p class="clearboth" style="padding:30px 0 0 0"><small></small></p>
                <?php
                if ($offer['Country']['uevat'] && !$has_vat)
                    echo "<b>Services not submitted to VAT in Belgium according to art. 21, §7°, a) of VAT legal text</b>";
                ?>
            </div>
            <button style="float:right" onclick="location.href='<?php echo $url;?>'">Download PDF</button>
        </div>
    </body>
</html>