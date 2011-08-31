<?php
$leadSources = array('Website', 'Google', 'Prospection');
$leadStatus = array('Suspect', 'Prospect', 'Opportunity', 'Avangate', 'Client', 'Lost');
$probability = array('0', '25', '50', '75', '90', '100');
?>
<form id="leadmerge2" action="#" >
    <table class="details stripeme">
        <tr>
            <th></th>
            <th>From</th>
            <th></th>
            <th>To</th>
        </tr>
        <tr>
            <td><strong>Opportunity name</strong></td>
            <td><input name="data[From][Lead][name]" disabled type="text" value="<?php echo $from['Lead']['name']; ?>" class="editable"/></td>
            <td>
                <?php
                if ($from['Lead']['name'] != $to['Lead']['name'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'input\', \'[Lead][name]\', this)"/>';
                ?>
            </td>
            <td><input name="data[To][Lead][name]" type="text" value="<?php echo $to['Lead']['name']; ?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Company name</strong></td>
            <td>
                <select name="data[From][Lead][company_id]" disabled >
                    <option value=""></option>
                    <?php
                    foreach ($companies as $key => $value)
                        if ($key == $from['Company']['id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td>
                <?php
                if ($from['Company']['id'] != $to['Company']['id'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'select\', \'[Lead][company_id]\', this)"/>';
                ?>
            </td>
            <td>
                <select name="data[To][Lead][company_id]" class="select">
                    <option value=""></option>
                    <?php
                    foreach ($companies as $key => $value)
                        if ($key == $to['Company']['id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Lead Status</strong></td>
            <td>
                <select name="data[From][Lead][status]" disabled>
                    <?php
                    foreach ($leadStatus as $value)
                        if (strtolower($value) == strtolower($from['Lead']['status']))
                            echo '<option SELECTED>' . $value . '</option>';
                        else
                            echo '<option>' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td>
                <?php
                if ($from['Lead']['status'] != $to['Lead']['status'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'select\', \'[Lead][status]\', this)"/>';
                ?>
            </td>
            <td>
                <select name="data[To][Lead][status]" class="select">
                    <?php
                    foreach ($leadStatus as $value)
                        if (strtolower($value) == strtolower($to['Lead']['status']))
                            echo '<option SELECTED>' . $value . '</option>';
                        else
                            echo '<option>' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Lead source</strong></td>
            <td>
                <select name="data[From][Lead][source]" disabled>
                    <option value=""></option>
                    <?php
                    foreach ($leadSources as $value)
                        if ($value == $from['Lead']['source'])
                            echo '<option SELECTED>' . $value . '</option>';
                        else
                            echo '<option>' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td>
                <?php
                if ($from['Lead']['source'] != $to['Lead']['source'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'select\', \'[Lead][source]\', this)"/>';
                ?>
            </td>
            <td>
                <select name="data[To][Lead][source]" class="select">
                    <option value=""></option>
                    <?php
                    foreach ($leadSources as $value)
                        if ($value == $to['Lead']['source'])
                            echo '<option SELECTED>' . $value . '</option>';
                        else
                            echo '<option>' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>        
        <tr>
            <td><strong>Assigned to</strong></td>
            <td>
                <select name="data[From][Lead][user_id]" disabled>
                    <option value=""></option>
                    <?php
                    foreach ($users as $key => $value)
                        if ($key == $from['User']['id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
            <td>
                <?php
                if ($from['User']['id'] != $to['User']['id'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'select\', \'[Lead][user_id]\', this)"/>';
                ?>
            </td>
            <td>
                <select name="data[To][Lead][user_id]" class="select">
                    <option value=""></option>
                    <?php
                    foreach ($users as $key => $value)
                        if ($key == $to['User']['id'])
                            echo '<option value="' . $key . '" SELECTED>' . $value . '</option>';
                        else
                            echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Amount</strong></td>
            <td>USD <input name="data[From][Lead][amount]" disabled type="text" value="<?php echo $from['Lead']['amount']; ?>" class="editable"/></td>
            <td>
                <?php
                if ($from['Lead']['amount'] != $to['Lead']['amount'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'input\', \'[Lead][amount]\', this)"/>';
                ?>
            </td>
            <td>USD <input name="data[To][Lead][amount]" type="text" value="<?php echo $to['Lead']['amount']; ?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Probability</strong></td>
            <td>
                <select name="data[From][Lead][probability]" disabled class="select">
                    <?php
                    foreach ($probability as $value)
                        if ($value == $from['Lead']['probability'])
                            echo '<option value="' . $value . '" SELECTED>' . $value . ' %</option>';
                        else
                            echo '<option value="' . $value . '">' . $value . ' %</option>';
                    ?>
                </select>
            </td>
            <td>
                <?php
                if ($from['Lead']['probability'] != $to['Lead']['probability'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'select\', \'[Lead][probability]\', this)"/>';
                ?>
            </td>
            <td>
                <select name="data[To][Lead][probability]" class="select">
                    <?php
                    foreach ($probability as $value)
                        if ($value == $to['Lead']['probability'])
                            echo '<option value="' . $value . '" SELECTED>' . $value . ' %</option>';
                        else
                            echo '<option value="' . $value . '">' . $value . ' %</option>';
                    ?>
                </select>
            </td>
        </tr>             
        <tr>
            <td><strong>Description</strong></td>
            <td><textarea name="data[From][Lead][description]" disabled class="opportunity_description"><?php echo $from['Lead']['description']; ?></textarea></td>
            <td>
                <?php
                if ($from['Lead']['description'] != $to['Lead']['description'])
                    echo '<img src="img/icons/right.png" alt="" onclick="opportunityMergeDetails(\'textarea\', \'[Lead][description]\', this)"/>';
                ?>
            </td>
            <td><textarea name="data[To][Lead][description]" class="opportunity_description"><?php echo $to['Lead']['description']; ?></textarea></td>
        </tr>
    </table>
</form>