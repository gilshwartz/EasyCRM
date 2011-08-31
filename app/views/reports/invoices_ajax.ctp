<div class="div1000" >
    <h1 class="datepicker">Invoices</h1>
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
<table cellpadding="0" cellspacing="0" class="stripeme">
    <thead>
        <tr>
            <th class="uppercase"><?php echo $this->Paginator->sort('Order#', 'Offer.invoice_id'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Company', 'Offer.billing_company'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Name', 'Offer.billing_name'); ?></th>
            <th class="uppercase">VAT excl.</th>
            <th class="uppercase"><?php echo $this->Paginator->sort("Date", 'Offer.date_paid'); ?></th>
            <th class="uppercase"></th>
        </tr>
    </thead>
    <?php
    foreach ($results as $offer) {
        echo '<tr>';

        echo '<td>'.$offer['Offer']['invoice_id'].'</td>';
        echo '<td>'.$offer['Offer']['billing_company'].'</td>';
        echo '<td>'.$offer['Offer']['billing_name'].'</td>';
        $amount = 0;
        foreach ($offer['OffersDetail'] as $detail) {
            $amount = $detail['amount'] + $amount;
        }
        echo '<td>'.$offer['Offer']['currency'].' '.$amount.'</td>';
        echo '<td>'.date('Y-m-d', strtotime($offer['Offer']['date_paid'])).'</td>';
        echo '<td>';
        echo '<button class="button" onclick="offerView(' . $offer['Offer']['id'] . ');">View details</button>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
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