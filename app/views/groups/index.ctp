<?php
    echo $this->element('submenu/settings');
?>
<script type="text/javascript">
setActiveSubmenu("snav2");
</script>
<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">Groups :</h1>
    <div id="datepicker">
      <!--<input type="text" class="input" style="font-size:0.9em;"/>-->
      <button class="button" onclick="groupAdd();">New Group</button>
    </div>
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
<table cellpadding="0" cellspacing="0" class="stripeme">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('parent_id');?></th>
            <th><?php echo $this->Paginator->sort('description'); ?></th>
            <th class="actions"><?php __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($groups as $group) {
            echo '<tr>';
            echo '<td>' . $group['Group']['name'] . '</td>';
            echo '<td>' . $group['ParentGroup']['name']. '</td>';
            echo '<td>' . $group['Group']['description'] . '</td>';
            echo '<td class="actions">';
            echo '<button class="button" onclick="groupEdit('.$group['Group']['id'].')">Edit</button>&nbsp;&nbsp;';
            echo '<button class="button" onclick="groupDelete('.$group['Group']['id'].')">Delete</button>';
            echo '</td>';
            echo '</tr>';
        } ?>
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
<script>
    paginator();
</script>