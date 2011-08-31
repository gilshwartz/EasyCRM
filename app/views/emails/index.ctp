<div class="div1000">
    <h1 class="datepicker">Emails</h1>
    <div id="datepicker">
        <button class="button" onclick="emailFetch();">Import Emails</button>
    </div>
</div>
<div class="paginator"  style="margin-top:70px">
    <!-- Shows the page numbers -->
    <?php echo $this->Paginator->numbers(); ?>
    <!-- Shows the next and previous links -->
    <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>
    <!-- prints X of Y, where X is current page and Y is number of pages -->
    <?php echo $this->Paginator->counter(); ?>
</div>
<table class="stripeme" id="emailsdata" cellpadding="0" cellspacing="0" width="50%">
    <thead>
        <tr>
            <th class="uppercase"><?php echo $this->Paginator->sort('Date', 'Email.date');?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('From', 'From.firstname');?></th>
            <th class="uppercase">To</th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Subject', 'Email.subject');?></th>
            <th class="uppercase"><?php echo $this->Paginator->sort('Opportunity', 'Lead.id');?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($emails as $email) {
            echo '<tr>';
                echo '<td>'.$email['Email']['date'].'</td>';
                echo '<td>'.$email['From']['fullname'].'</td>';
                echo '<td>'.$email['To'][0]['fullname'].'</td>';
                echo '<td>'.substr($email['Email']['subject'], 0, 50).'...</td>';
                if ($email['Email']['lead_id'] != NULL)
                    echo '<td><img src="img/icons/accept.png" /></td>';
                else
                    echo '<td><img src="img/icons/cross.png" /></td>';
                echo '<td>';
                    if ($email['Email']['lead_id'] != NULL)
                        echo '<button class="button" onclick="requestShowLead('.$email['Email']['lead_id'].')">Opportunity</button>&nbsp;';
                    echo '<button class="button" onclick="viewEmail('.$email['Email']['id'].')">View</button>&nbsp;';
                    echo '<button class="button" onclick="emailDelete('.$email['Email']['id'].')">Delete</button>';
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