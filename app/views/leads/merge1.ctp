<form id="leadmerge1" action="#" >
    <table class="details stripeme">
        <tr>
            <td><strong>From Opportunity :</strong></td>
            <td>
                <select name="from">
                    <option value=""></option>
                    <?php
                    foreach ($leads as $key => $value)
                        echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>To Opportunity :</strong></td>
            <td>
                <select name="to">
                    <option value=""></option>
                    <?php
                    foreach ($leads as $key => $value)
                        echo '<option value="' . $key . '">' . $value . '</option>';
                    ?>
                </select>
            </td>
        </tr>
    </table>
</form>