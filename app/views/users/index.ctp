<?php
    echo $this->element('submenu/settings');
?>
<script type="text/javascript">
setActiveSubmenu("snav1");
</script>
<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">Users :</h1>
    <div id="datepicker">
      <!--<input type="text" class="input" style="font-size:0.9em;"/>-->
        <button class="button" onclick="userAdd();">New User</button>
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
            <th><?php echo $this->Paginator->sort('username'); ?></th>
            <th><?php echo $this->Paginator->sort('firstname'); ?></th>
            <th><?php echo $this->Paginator->sort('lastname'); ?></th>
            <th><?php echo $this->Paginator->sort('email'); ?></th>
            <th><?php echo $this->Paginator->sort('group_id'); ?></th>
            <th><?php echo $this->Paginator->sort('role_id'); ?></th>
            <th><?php echo $this->Paginator->sort('active'); ?></th>
            <th class="actions"><?php __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($users as $user) {
            echo '<tr>';
            echo '<td>' . $user['User']['username'] . '</td>';
            echo '<td>' . $user['User']['firstname'] . '</td>';
            echo '<td>' . $user['User']['lastname'] . '</td>';
            echo '<td>' . $user['User']['email'] . '</td>';
            echo '<td>' . $user['Group']['name'] . '</td>';
            echo '<td>' . $user['Role']['name'] . '</td>';
            if ($user['User']['active']) {
                echo '<td><img src="img/icons/accept.png" /></td>';
            } else {
                echo '<td><img src="img/icons/cross.png" /></td>';
            }
            echo '<td class="actions">';
            echo '<button class="button" onclick="userEdit(' . $user['User']['id'] . ', 0, 1)">Edit</button>&nbsp;&nbsp;';
            echo '<button class="button" onclick="userPassword(' . $user['User']['id'] . ')">Password</button>&nbsp;&nbsp;';
            if ($session->read('Auth.User.id') != $user['User']['id'])
                echo '<button class="button" onclick="userDelete(' . $user['User']['id'] . ')">Delete</button>';
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
<div class="modal" id="password">
    
</div>
<script>
    paginator();
</script>