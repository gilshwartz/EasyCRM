<form action="#" id="product2edit">
    <table cellpadding="0" cellspacing="0" class="details stripeme">
        <tr>
            <td><strong>ID</strong></td>
            <td><?php echo $product['Product']['id'];?></td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><input type="text" name="data[Product][name]" value="<?php echo $product['Product']['name'];?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Price</strong></td>
            <td><input type="text" name="data[Product][price]" value="<?php echo $product['Product']['price'];?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Codename</strong></td>
            <td><input type="text" name="data[Product][codename]" value="<?php echo $product['Product']['codename'];?>" class="editable"/></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><textarea name="data[Product][description]"><?php echo $product['Product']['description'];?></textarea></td>
        </tr>
        <tr>
            <td><strong>Active</strong></td>
            <td>
                <?php
                if ($product['Product']['active'])
                    echo '<input type="checkbox" name="data[Product][active]" checked/>';
                else
                    echo '<input type="checkbox" name="data[Product][active]"/>';
                ?>
           </td>
        </tr>
    </table>
</form>