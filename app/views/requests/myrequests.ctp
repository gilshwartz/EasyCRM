<div id="submenu" class="portfolio">
    <a href="#" onclick="return loadPage('leads/myopportunities')">My opportunities</a> |
    <span class="active">My Requests</span> |
    <a href="#" onclick="return loadPage('events/mytasks')">My Tasks</a> |
    <a href="#" onclick="return loadPage('licenses/mytrials')">My Trials</a> |
    <a href="#" onclick="return loadPage('leads/mywons')">My Wons</a> |
    <a href="#" onclick="return loadPage('leads/mylosts')">My Losts</a>
</div>

<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">My requests</h1>
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

<table class="stripeme" cellpadding="0" cellspacing="0" width="50%" id="requests">
    <tr>
        <th class="uppercase"><?php echo $this->Paginator->sort('Date', 'Request.dateIn'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Type', 'Request.type'); ?></th>
        <th class="uppercase">Product</th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Full name', 'Contact.firstname'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Company', 'Contact.company_name'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Country', 'Contact.country'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Existing opportunity', 'LeadsRequest.id'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Opportunity owner', 'User.firstname'); ?></th>
        <th width="200px"></th>
    </tr>
    <?php
    foreach ($requests as $request) {
        if (!$request['Request']['accepted'])
            echo '<tr id="' . $request['Request']['id'] . '" style="font-weight: bold;" >';
        else
            echo '<tr id="' . $request['Request']['id'] . '">';
        echo '<td>' . date('Y-m-d', strtotime($request['Request']['dateIn'])) . '</td>';
        echo '<td>' . $request['Request']['type'] . '</td>';
        echo '<td>';
        if (isset($request['RequestTrial']['product']) && $request['RequestTrial']['product'] != '')
            switch ($request['RequestTrial']['product']) {
                case "PlanningForce Server Edition" : echo '<img src="img/icons/products/icon_collaborative.png"/>';
                    break;
                case "PlanningForce Program Manager" : echo '<img src="img/icons/products/icon_manager.png"/>';
                    break;
                case "PlanningForce Portfolio Planner" : echo '<img src="img/icons/products/icon_portfolio.png"/>';
                    break;
                case "PlanningForce Express Planner" : echo '<img src="img/icons/products/icon_express.png"/>';
                    break;
            } else if (isset($request['RequestQuote']['product']) && $request['RequestQuote']['product'] != '')
            switch ($request['RequestQuote']['product']) {
                case "PlanningForce Server Edition" : echo '<img src="img/icons/products/icon_collaborative.png"/>';
                    break;
                case "PlanningForce Program Manager" : echo '<img src="img/icons/products/icon_manager.png"/>';
                    break;
                case "PlanningForce Portfolio Planner" : echo '<img src="img/icons/products/icon_portfolio.png"/>';
                    break;
                case "PlanningForce Express Planner" : echo '<img src="img/icons/products/icon_express.png"/>';
                    break;
            }
        else
            echo 'n/a';
        echo '</td>';
        echo '<td>' . $request['Contact']['fullname'] . '</td>';
        echo '<td>' . $request['Contact']['company_name'] . '</td>';
        echo '<td>' . $countries[$request['Contact']['country']] . '</td>';
        echo '<td>';
        if (isset($request['LeadsRequest']['id']) && $request['LeadsRequest']['id'] != '')
            echo '<img src="img/icons/accept.png" />';
        else
            echo '<img src="img/icons/cross.png" />';
        echo '</td>';
        echo '<td>' . $request['Group']['name'] . ' -<br/>' . $request['User']['firstname'] . ' ' . $request['User']['lastname'] . '</td>';
        echo '<td class="buttons2">';
        echo '<button class="button" onclick="requestAssign(' . $request['Request']['id'] . ');">Assign</button>&nbsp;&nbsp';
        echo '<button class="button" onclick="requestDetails(' . $request['Request']['id'] . ');">View details</button>&nbsp;&nbsp';
        echo '<button class="button" onclick="requestDelete(' . $request['Request']['id'] . ')">Delete</button>';
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

<script>
    paginator();
</script>
<!-- END assign request modal -->
