<div class="div1000">
    <h1 class="datepicker">Companies</h1>
    <div id="datepicker">
        <button class="button" onclick="companyAdd();">Add Company</button>
    </div>
</div>
<div class="paginator"   style="margin-top:70px">
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
            <th class="uppercase"><?php echo $this->Paginator->sort('Name', 'Company.name');?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Description', 'Company.description');?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Phone', 'Company.phone');?></th>
            <th class="uppercase">Leads</th>
            <th class="uppercase"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($companies as $company) {
            echo '<tr>';
            echo '<td>' . $company['Company']['name'] . '</td>';
            echo '<td>' . $company['Company']['description'] . '</td>';
            echo '<td>' . $company['Company']['phone'] . '</td>';
            echo '<td>' . sizeof($company['Lead']) . '</td>';
            echo '<td>';
                echo '<button class="button" onclick="companyEdit('.$company['Company']['id'].');">Edit</button>&nbsp;&nbsp;';
                echo '<button class="button" onclick="companyLeads('.$company['Company']['id'].');">Leads</button>&nbsp;&nbsp;';
                if ($editable)
                    echo '<button class="button" onclick="companyDelete('.$company['Company']['id'].');">Delete</button>&nbsp;&nbsp;';
            echo '</td>';
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