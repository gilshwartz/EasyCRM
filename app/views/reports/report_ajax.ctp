
<div class="div1000" >
    <h1 class="datepicker"><?php echo ucfirst($results['type']); ?></h1>
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

<table class="stripeme" cellpadding="0" cellspacing="0" width="60%" id="requests" style="font-size: 75%;">
    <tr>
        <th class="uppercase"><?php echo $this->Paginator->sort('Date', 'Request.dateIn'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Type', 'Request.type'); ?></th>
        <th class="uppercase">Product</th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Full name', 'Contact.firstname'); ?></th>
        <th class="uppercase">Email</th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Company', 'Contact.company_name'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Country', 'Contact.country'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Lead?', 'LeadsRequest.id'); ?></th>
        <th class="uppercase"><?php echo $this->Paginator->sort('Manager?', 'User.firstname'); ?></th>
        <th width="200px"></th>
    </tr>
    <?php
    foreach ($results['requests'] as $request) {
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
        echo '<td>' . $request['Contact']['firstname'] . ' ' . $request['Contact']['lastname'] . '</td>';
        $emails = unserialize($request['Contact']['emails']);
        if ($emails === false) {
            $emails[0] = $contact['Contact']['emails'];
            $emails[1] = "";
        }
        echo '<td>' . $emails[0] . '</td>';
        echo '<td>' . $request['Contact']['company_name'] . '</td>';
        echo '<td>' . $results['countries'][$request['Contact']['country']] . '</td>';
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

<!-- BEGIN assign request modal -->
<div class="modal" id="requestassign">
    <form action="" >
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>1. Group</td>
                <td>
                    <select name="data[Request][partner]">
                        <option value=""></option>
                        <?php
                        foreach ($groups as $key => $value)
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>2. Account Manager</td>
                <td>
                    <select name="data[Request][user]">
                        <option value=""></option>
                        <?php
                        foreach ($users as $key => $value)
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <input type="hidden" name="data[Request][accpeted]" value="0"/>
    </form>
</div>
<script>
    paginator();
</script>
<!-- END assign request modal -->
