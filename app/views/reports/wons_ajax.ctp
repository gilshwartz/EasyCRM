<div class="div1000" >
    <h1 class="datepicker">Wons</h1>
</div>
<div class="paginator" style="margin-top:70px">
    <!-- Shows the page numbers -->
    <?php echo $this->Paginator->numbers(); ?>
    <!-- Shows the next and previous links -->
    <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>
    <!-- prints X of Y, where X is current page and Y is number of pages -->
    <?php echo $this->Paginator->counter(); ?>
</div>
<table class="stripeme" id="opportunitydata" cellpadding="0" cellspacing="0" width="50%">
    <thead>
        <tr>
            <th class="uppercase"><?php echo $this->Paginator->sort('Status', 'Lead.status'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Opportunity', 'Lead.name'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Forecast close', 'Lead.forecast_close'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Deal size', 'Lead.amount'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort("Probability", 'Lead.probability'); ?></th>
            <th class="uppercase">Weighted<br />forecast</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $weighted = 0;
        foreach ($results as $lead) {
            $total = $total + $lead['Lead']['amount'];
            $weighted = $weighted + round($lead['Lead']['amount'] * $lead['Lead']['probability'] / 100, 2);
            echo '<tr>';
            echo '<td>' . $lead['Lead']['status'] . '</td>';
            echo '<td><a href="#" onclick="return loadPage(\'leads/view/' . $lead['Lead']['id'] . '\');">' . $lead['Lead']['name'] . '</a></td>';
            $date = $lead['Lead']['forecast_close'];
            if ($date != '')
                echo '<td>' . date('Y-m-d', strtotime($lead['Lead']['forecast_close'])) . '</td>';
            else
                echo '<td></td>';
            echo '<td>$ ' . $lead['Lead']['amount'] . '</td>';
            echo '<td>' . $lead['Lead']['probability'] . '%</td>';
            echo '<td>$ ' . round($lead['Lead']['amount'] * $lead['Lead']['probability'] / 100, 2) . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr class="total">
            <td colspan="3" class="uppercase alignleft">Total</td>
            <td colspan="1">$ <?php echo $total; ?></td>
            <td></td>
            <td>$ <?php echo $weighted; ?></td>
        </tr>
    </tfoot>
</table>
<div class="paginator">
    <!-- Shows the page numbers -->
    <?php echo $this->Paginator->numbers(); ?>
        <!-- Shows the next and previous links -->
    <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>
        <!-- prints X of Y, where X is current page and Y is number of pages -->
    <?php echo $this->Paginator->counter(); ?>
</div>
<script language="javascript">
    $(function() {
        paginator();
    });
</script>