<div id="submenu" class="portfolio">
    <a href="#" onclick="return loadPage('leads/myopportunities')">My opportunities</a> |
    <a href="#" onclick="return loadPage('requests/myrequests')">My Requests</a> |
    <span class="active">My Tasks</span> |
    <a href="#" onclick="return loadPage('licenses/mytrials')">My Trials</a> |
    <a href="#" onclick="return loadPage('leads/mywons')">My Wons</a> |
    <a href="#" onclick="return loadPage('leads/mylosts')">My Losts</a>
</div>

<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">My tasks</h1>
</div>

<div class="paginator">
    <!-- Shows the page numbers -->
    <?php echo $this->Paginator->numbers(); ?>
    <!-- Shows the next and previous links -->
    <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>
    <!-- prints X of Y, where X is current page and Y is number of pages -->
    <?php echo $this->Paginator->counter(); ?>
</div>

<table id="tasks" class="stripeme" cellpadding="0" cellspacing="0" width="50%">
    <thead>
        <tr>
            <th class="uppercase"><?php echo $this->Paginator->sort('Start Date', 'Event.start_date'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('End Date', 'Event.end_date'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Type', 'Event.type'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Opportunity name', 'Lead.name'); ?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Subject', 'Event.subject'); ?></th>
            <th class="uppercase"></th>
        </tr>
    </thead>
    <?php
    foreach ($events as $event) {
        echo '<tr id="' . $event['Event']['id'] . '">';
        if ($event['Event']['start_date'] != '0000-00-00 00:00:00')
            if ($event['Event']['closed'])
                echo '<td style="text-decoration: line-through;">' . $event['Event']['start_date'] . '</td>';
            else
                echo '<td style="">' . $event['Event']['start_date'] . '</td>';
        else
            echo '<td></td>';

        if ($event['Event']['end_date'] != '0000-00-00 00:00:00')
            if ($event['Event']['closed'])
                echo '<td style="text-decoration: line-through;">' . $event['Event']['end_date'] . '</td>';
            else
                echo '<td style="">' . $event['Event']['end_date'] . '</td>';
        else
            echo '<td></td>';

        if ($event['Event']['closed'])
            echo '<td style="text-decoration: line-through;">' . $event['Event']['type'] . '</td>';
        else
            echo '<td style="">' . $event['Event']['type'] . '</td>';

        if ($event['Event']['closed'])
            echo '<td style="text-decoration: line-through;"><a href="#"  onclick="return loadPage(\'leads/view/' . $event['Lead']['id'] . '\');">' . $event['Lead']['name'] . '</a></td>';
        else
            echo '<td style=""><a href="#"  onclick="return loadPage(\'leads/view/' . $event['Lead']['id'] . '\');">' . $event['Lead']['name'] . '</a></td>';

        if ($event['Event']['closed'])
            echo '<td style="text-decoration: line-through;">' . $event['Event']['subject'] . '</td>';
        else
            echo '<td style="">' . $event['Event']['subject'] . '</td>';

        echo '<td id="actions">';
        if (!$event['Events']['closed'])
            echo '<button class="button" onclick="opportunityDoneEvent(' . $event['Events']['id'] . ')">Mark as done</button>&nbsp;&nbsp;';
        else
            echo '<button class="button" onclick="opportunityDoneEvent(' . $event['Events']['id'] . ')">Mark as not done</button>&nbsp;&nbsp;';
        echo '<button class="button" onclick="opportunityEditEvent(' . $event['Event']['id'] . ')">Update</button>&nbsp;&nbsp;';
        echo '<button class="button" onclick="opportunityDeleteEvent(' . $event['Event']['id'] . ')">Delete</button>';
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

<!-- BEGIN mark as done -->
<div class="modal" id="markasdone" title="Mark a task as done">
    	Do you confirm this task has been done / not done?
</div>
<!-- END mark as done -->

<script>
    paginator();
    $(function(){
        $('.input').daterangepicker({arrows:true});                
    });
</script>

