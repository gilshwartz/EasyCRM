<table width="300px">
    <tr>
        <td><strong>Date</strong></td>
        <td><?php echo $request['Request']['dateIn']; ?></td>
    </tr>
    <tr>
        <td><strong>Company</strong></td>
        <td><?php echo $request['Contact']['company_name']; ?></td>
    </tr>
    <tr>
        <td><strong>First name</strong></td>
        <td><?php echo $request['Contact']['firstname']; ?></td>
    </tr>
    <tr>
        <td><strong>Last name</strong></td>
        <td><?php echo $request['Contact']['lastname']; ?></td>
    </tr>
    <tr>
        <td><strong>Email</strong></td>
        <td>
            <?php
            if ($emails = unserialize($request['Contact']['emails']))
                echo $emails[0];
            else
                echo $request['Contact']['emails'];
            ?>
        </td>
    </tr>
    <tr>
        <td><strong>Phone</strong></td>
        <td>
            <?php
            if ($phones = unserialize($request['Contact']['phones']))
                echo $phones[0];
            else
                echo $request['Contact']['phones'];
            ?>
        </td>
    </tr>
    <tr>
        <td><strong>Country</strong></td>
        <td><?php echo $countries[$request['Contact']['country']]; ?></td>
    </tr>
    <?php
            if (isset($request['RequestContact']['subject']) && $request['RequestContact']['subject'] != "") {
                echo '<tr>';
                echo '<td><strong>Subject</strong></td>';
                echo '<td>' . $request['RequestContact']['subject'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Message</strong></td>';
                echo '<td>' . $request['RequestContact']['message'] . '</td>';
                echo '</tr>';
            } else if (isset($request['RequestTrial']['product']) && $request['RequestTrial']['product'] != "") {
                echo '<tr>';
                echo '<td><strong>Product</strong></td>';
                echo '<td>' . $request['RequestTrial']['product'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Message</strong></td>';
                echo '<td>' . $request['RequestTrial']['message'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Installation ID</strong></td>';
                echo '<td>' . $request['RequestTrial']['installation_id'] . '</td>';
                echo '</tr>';
            } else if (isset($request['RequestQuote']['product']) && $request['RequestQuote']['product'] != "") {
                echo '<tr>';
                echo '<td><strong>Product</strong></td>';
                echo '<td>' . $request['RequestQuote']['product'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>License Usage</strong></td>';
                echo '<td>' . $request['RequestQuote']['lciense_usage'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Amount</strong></td>';
                echo '<td>' . $request['RequestQuote']['amount'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Resources</strong></td>';
                echo '<td>' . $request['RequestQuote']['resources'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Projects</strong></td>';
                echo '<td>' . $request['RequestQuote']['projects'] . '</td>';
                echo '</tr>';
            }
    ?>
        </table>
<br/><br/>
Please use the following link to connect the <strong>PlanningForce CRM</strong> : <br/>
<a href="https://secure.planningforce.com">https://secure.planningforce.com</a>