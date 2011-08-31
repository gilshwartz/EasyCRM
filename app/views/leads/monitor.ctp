<?php
if (!$is_partner)
    echo $this->element('submenu/opportunities');
else
        echo $this->element('submenu/opportunities_partner');
?>
<script type="text/javascript">
setActiveSubmenu("snav4");
</script>
<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">My Account Managers</h1>
    <div id="datepicker">
        <select class="select" id="amgroups" onchange="loadAMs($('#amgroups').val());">
            <?php
            foreach ($groups as $key => $value)
                echo '<option value="' . $key . '">' . $value . '</option>';
            ?>
        </select>
    </div>
</div>
<div id="ammanager"></div>
<script type="text/javascript">
    $('select.select').selectmenu();

    function loadAMs(id) {
        $('#ammanager').load("leads/monitordetails/" + id);
    }

    loadAMs($('#amgroups').val());
</script>
