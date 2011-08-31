<head>
    <title>Store - PlanningForce</title>
    <?php echo $this->Html->css('/store/css/invoice'); ?>
</head>
<body>
    <div id="container">
        <form method="POST" action="">
            <div id="left_big" class="clearboth">
                <?php echo $html->image('/store/img/planningforce.png', array('alt' => 'PlanningForce')) ?>
                <div class="quoteinfo">
                    <strong>ISC - PlanningForce</strong><br/>
                    Chauss&eacute;e de Nivelles, 121/2<br/>
                    7181 Arquennes - Belgium<br/>
                    <strong>Phone:</strong> +32 67 550 224<br/>
                    <strong>Email:</strong> <a href="mailto:sales@planningforce.com">sales@planningforce.com</a>
                    <br  /><strong>V.A.T:</strong> BE 0473.176.292
                </div>

                <h1 class="clearboth">Plugins Store : </h1>

                <div class="quoteaddress">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" class="tableinside">
                                    <tr>
                                        <td><strong>Firstname</strong></td>
                                        <td><input type="text" name="data[Contact][firstname]"/></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lastname</strong></td>
                                        <td><input type="text" name="data[Contact][lastname]"/></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td><input type="text" name="data[Contact][emails]"/></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Company</strong></td>
                                        <td><input type="text" name="data[Offer][billing_company]"/></td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table cellspacing="0" cellpadding="0" class="tableinside">
                                    <tr>
                                        <td><strong>Address</strong></td>
                                        <td><textarea name="data[Offer][billing_address]"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Country</strong></td>
                                        <td>
                                            <select name="data[Offer][billing_country_id]" class="select" onchange="$(this).removeClass('ui-state-error');">
                                                <?php
                                                foreach ($countries as $key => $value)
                                                    echo '<option value=' . $key . '>' . $value . '</option>';
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Professionnal</strong></td>
                                        <td><input type="checkbox" name="data[Offer][is_pro]" checked/></td>
                                    </tr>

                                    <tr>
                                        <td><strong>VAT number</strong></td>
                                        <td><input type="text" name="data[Offer][billing_vat]"/></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table> <br/>
                    <strong>Your installation ID : </strong>
                    <input type="text" name="data[PluginsLead][installation_id]" value="<?php echo $installation_id; ?>" />
                    <input type="hidden" name="data[PluginsLead][plugin]" value="<?php echo $plugin['Plugin']['id']; ?>" />
                </div>
                <table cellpadding="0" cellspacing="0" class="details stripeme">
                    <thead>
                        <tr>
                            <th>Plugin Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $plugin['Plugin']['name']; ?></td>
                            <td>1</td>
                            <td>USD <?php echo $plugin['Plugin']['price']; ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Total excl. VAT</th>
                            <th><?php echo $plugin['Plugin']['price']; ?></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <input type="submit" name="Next" />
        </form>
    </div>
</body>