<div class="div1000" >
    <h1 class="datepicker">History</h1>
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
            <th>Title</th>
            <th style="width: 140px">Date</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results as $log) {
            echo '<tr>';
            echo '<td>' . $log['Log']['title'] . '</td>';
            echo '<td>' . date('D, d M Y H:i:s', strtotime($log['Log']['created'])) . '</td>';
            echo '<td>' . $log['Log']['description'] . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
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