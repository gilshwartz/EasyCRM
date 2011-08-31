<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>Invoice Preview - PlanningForce</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/invoice.css" media="screen"/>
        <title>Invoice</title>
    </head>
    <body>
        <div id="container">
            <div id="left_big" class="clearboth">
                <img src="img/planningforce.png" class="logo"/>
                <div class="quoteinfo">
                    <strong>ISC - PlanningForce</strong><br/>
                    Chauss&eacute;e de Nivelles, 121/2<br/>
                    7181 Arquennes - Belgium<br/>
                    <strong>Phone:</strong> +32 67 550 224<br/>
                    <strong>Email:</strong> <a href="mailto:sales@planningforce.com">sales@planningforce.com</a>
                    <br  /><strong>V.A.T:</strong> BE 0473.176.292
                </div>

                <h1 class="clearboth">Quote <?php echo $offer['Offer']['invoice_id']; ?></h1>

                <div class="quoteaddress">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" class="tableinside">
                                    <tr>
                                        <td><strong>Order Reference</strong></td>
                                        <td><?php echo $offer['Offer']['order_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Company</strong></td>
                                        <td><?php echo $offer['Offer']['billing_company']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Name</strong></td>
                                        <td><?php echo $offer['Offer']['billing_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>VAT number</strong></td>
                                        <td><?php echo $offer['Offer']['billing_vat']; ?></td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table cellspacing="0" cellpadding="0" class="tableinside">
                                    <tr>
                                        <td><strong>Address</strong></td>
                                        <td><?php echo nl2br($offer['Offer']['billing_address']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Country</strong></td>
                                        <td><?php echo $offer['Country']['name']; ?></td>
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
                            echo '<tr>';
                            echo '<td>' . $detail['product_name'] . '</td>';
                            echo '<td>' . $offer['Offer']['currency'] . ' ' . $detail['unit_price'] . '</td>';
                            echo '<td>' . $detail['quantity'] . '</td>';
                            echo '<td>' . $detail['discount'] . '</td>';
                            echo '<td>' . $offer['Offer']['currency'] . ' ' . $detail['amount'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3"></th>
                            <th>Total excl. VAT</th>
                            <th><?php echo $offer['Offer']['currency'] . ' ' . round($subtotal,2); ?></th>
                        </tr>
                        <tr>
                            <th colspan="3"></th>
                            <th>VAT amount</th>
                            <th>
                                <?php
                                if ($has_vat)
                                    $vat = ($subtotal * 0.21);
                                echo $offer['Offer']['currency'] . ' ' . round($vat,2);
                                ?>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3"></th>
                            <th>Total incl. VAT</th>
                            <th><?php echo $offer['Offer']['currency'] . ' ' . round(($subtotal + $vat),2); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <p class="clearboth" style="padding:30px 0 0 0"><small></small></p>
                <?php
                $ogone['GLOBAL']['ORDERID'] = $offer['Offer']['order_id'];
                $ogone['GLOBAL']['AMOUNT'] = round(($subtotal + $vat),2) * 100;
                $ogone['GLOBAL']['CURRENCY'] = $offer['Offer']['currency'];
                ksort($ogone['GLOBAL']);

                $hash = "";
                foreach($ogone['GLOBAL'] as $key => $value) {
                    if ($value != "")
                        $hash = $hash . $key . "=" . $value . $ogone['CONFIG']['SHASIG'];
                }

                ?>
                <div id="paynow">
                    <form METHOD="post" ACTION="<?php echo $ogone['CONFIG']['URL'][$ogone['CONFIG']['MODE']]; ?>" id="ogoneform" name="form1">
                        <input type="hidden" NAME="PSPID" value="<?php echo $ogone['GLOBAL']['PSPID']; ?>"/>
                        <input type="hidden" NAME="LANGUAGE" value="<?php echo $ogone['GLOBAL']['LANGUAGE']; ?>"/>
                        <input type="hidden" NAME="ORDERID" value="<?php echo $ogone['GLOBAL']['ORDERID']; ?>"/>
                        <input type="hidden" NAME="AMOUNT" value="<?php echo $ogone['GLOBAL']['AMOUNT'] ?>"/>
                        <input type="hidden" NAME="CURRENCY" value="<?php echo $ogone['GLOBAL']['CURRENCY']; ?>"/>
                        
                        <!-- lay out information -->
                        <input type="hidden" NAME="TITLE" value="<?php echo $ogone['GLOBAL']['TITLE']; ?>"/>
                        <input type="hidden" NAME="BGCOLOR" value="<?php echo $ogone['GLOBAL']['BGCOLOR']; ?>"/>
                        <input type="hidden" NAME="TXTCOLOR" value="<?php echo $ogone['GLOBAL']['TXTCOLOR']; ?>"/>
                        <input type="hidden" NAME="TBLBGCOLOR" value="<?php echo $ogone['GLOBAL']['TBLBGCOLOR']; ?>"/>
                        <input type="hidden" NAME="TBLTXTCOLOR" value="<?php echo $ogone['GLOBAL']['TBLTXTCOLOR']; ?>"/>
                        <input type="hidden" NAME="BUTTONBGCOLOR" value="<?php echo $ogone['GLOBAL']['BUTTONBGCOLOR']; ?>"/>
                        <input type="hidden" NAME="BUTTONTXTCOLOR" value="<?php echo $ogone['GLOBAL']['BUTTONTXTCOLOR']; ?>"/>
                        <input type="hidden" NAME="FONTTYPE" value="<?php echo $ogone['GLOBAL']['FONTTYPE']; ?>"/>
                        <input type="hidden" NAME="LOGO" value="<?php echo $ogone['GLOBAL']['LOGO']; ?>"/>

                        <!-- post-payment redirection -->
                        <input type="hidden" name="ACCEPTURL" value="<?php echo $ogone['GLOBAL']['ACCEPTURL']; ?>"/>
                        <input type="hidden" name="DECLINEURL" value="<?php echo $ogone['GLOBAL']['DECLINEURL']; ?>"/>
                        <input type="hidden" name="EXCEPTIONURL" value="<?php echo $ogone['GLOBAL']['EXCEPTIONURL']; ?>"/>
                        <input type="hidden" name="CANCELURL" value="<?php echo $ogone['GLOBAL']['CANCELURL']; ?>"/>
                        <input type="hidden" NAME="BACKURL" VALUE="<?php echo $ogone['GLOBAL']['BACKURL']; ?>"/>

                        <!-- security check -->
                        <input type="hidden" NAME="SHASign" value="<?php echo sha1($hash);?>"/>
                        
                        <input type="submit" value="Pay now" id=submit2 name=submit2 style="font-size:14px;padding:3px"/>
                    </form>
                    <img src="img/logo_ogone.gif" />&nbsp;&nbsp;&nbsp;<img src="img/verisign_EN.gif" />
                </div>

            </div>
        </div>
    </body>
</html>