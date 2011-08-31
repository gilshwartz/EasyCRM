<?php
    echo $this->element('submenu/settings');
?>
<script type="text/javascript">
setActiveSubmenu("snav3");
</script>
<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">Products :</h1>
    <div id="datepicker">
      <!--<input type="text" class="input" style="font-size:0.9em;"/>-->
        <button class="button" onclick="productAdd();">New Product</button>
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
            <th><?php echo $this->Paginator->sort('price'); ?></th>
            <th><?php echo $this->Paginator->sort('codename'); ?></th>
            <th><?php echo $this->Paginator->sort('description'); ?></th>
            <th><?php echo $this->Paginator->sort('active'); ?></th>
            <th class="actions"><?php __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td>' . $product['Product']['name'] . '</td>';
            echo '<td>' . $product['Product']['price'] . '</td>';
            echo '<td>' . $product['Product']['codename'] . '</td>';
            echo '<td>' . $product['Product']['description'] . '</td>';
            if ($product['Product']['active']) {
                echo '<td><img src="img/icons/accept.png" /></td>';
            } else {
                echo '<td><img src="img/icons/cross.png" /></td>';
            }
            echo '<td class="actions">';
            echo '<button class="button" onclick="productEdit(' . $product['Product']['id'] . ')">Edit</button>&nbsp;&nbsp;';
            echo '<button class="button" onclick="productDelete(' . $product['Product']['id'] . ')">Delete</button>';
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