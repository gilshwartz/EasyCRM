<div title="Order Ref. <?php echo $orderRef; ?>">
    <form action="#" id="offerstep2">
        <div style="float: right">
            <img src="img/icons/add.png" alt="" onclick="addRow();" style="cursor: pointer"/>
            <img src="img/icons/refresh.png" alt="" onclick="recalculate();" style="cursor: pointer"/>
        </div>
        <div style="float: right; width: 100%">
            <table cellpadding="0" cellspacing="0" class="details stripeme">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Discount (%)</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="editable-select" name="name">
                                <?php
                                foreach ($products as $key => $value) {
                                    echo '<option id="' . $key . '">' . $value . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="price" onchange="recalculate();"  class="smalleditable"/>
                        </td>
                        <td>
                            <input type="text" name="qty" onchange="recalculate();" value="1"  class="smalleditable"/>
                        </td>
                        <td>
                            <input type="text" name="discount" onchange="recalculate();" value="0"  class="smalleditable"/>
                        </td>
                        <td>
                            <input type="text" name="total" readonly  class="smalleditable"/>
                        </td>
                        <td>
                            <img src="img/icons/cross.png" alt="" onclick="removeRow($(this).parents('tr'))" style="cursor: pointer"/>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3"></th>
                        <th>Total excl. VAT</th>
                        <th id="subtotal"></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th>VAT amount</th>
                        <th id="vat"></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th>Total incl. VAT</th>
                        <th id="total"></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="float: right">
            <label><strong>Currency :</strong></label>
            <select name="currency" onchange="changeCurrency($(this).val());">
                <option value="USD">US dollars</option>
                <option value="EUR">Euro</option>
            </select>
        </div>
    </form>
    <div style="display: none">
        <table id="newline">
            <tr>
                <td>
                    <select class="editable-select" name="name">
                        <?php
                                foreach ($products as $key => $value) {
                                    echo '<option id="' . $key . '">' . $value . '</option>';
                                }
                        ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="price" onchange="recalculate();"  class="smalleditable"/>
                        </td>
                        <td>
                            <input type="text" name="qty" onchange="recalculate();" value="1"  class="smalleditable"/>
                        </td>
                        <td>
                            <input type="text" name="discount" onchange="recalculate();" value="0"  class="smalleditable"/>
                        </td>
                        <td>
                            <input type="text" name="total" readonly  class="smalleditable"/>
                        </td>
                        <td>
                            <img src="img/icons/cross.png" alt="" onclick="removeRow($(this).parents('tr'))" style="cursor: pointer"/>
                        </td>
                    </tr>
                </table>
            </div>

            <script type="text/javascript">
                var has_vat = <?php echo $vat; ?>;
        var currency = "USD";

        function addRow() {
            $('#offerstep2 table tbody').append($('#newline tbody').html());
            editSelect();
        }

        function removeRow(row) {
            $(row).remove();
        }

        function recalculate() {
            var subtotal = 0;
            var vat = 0;
            var total = 0;

            $('#offerstep2 table tbody tr').each(function () {
                price = $('[name="price"]', this).val();
                qty = $('[name="qty"]', this).val();
                discount = $('[name="discount"]', this).val();
                rtotal = price * qty - (price * qty * discount /100);
                $('[name="total"]', this).val(rtotal);
                subtotal = subtotal + rtotal;
            });
            if (has_vat)
                vat = subtotal * 21 /100;
            total = subtotal + vat;

            $('#subtotal').text(currency + " " + subtotal);
            $('#vat').text(currency + " " + vat);
            $('#total').text(currency + " " + total);
        }

        function form2xml() {
            var xml = '<products currency="' + $('[name="currency"]').val() + '" >';

            $('#offerstep2 table tbody tr').each(function () {
                if ($('input[name="name"]', this).val() != "") {
                    xml += '<product>';
                    xml += '<name>' + $('input[name="name"]', this).val() + '</name>';
                    xml += '<price>' + $('[name="price"]', this).val() + '</price>';
                    xml += '<qty>' + $('[name="qty"]', this).val() + '</qty>';
                    xml += '<discount>' + $('[name="discount"]', this).val() + '</discount>';
                    xml += '</product>';
                }
            });

            xml += '</products>';
            return xml;
        }

        function changeCurrency(newCurrency) {
            currency = newCurrency;
            recalculate();
        }

        function onProductSelected(row) {
            $('td:eq(0) > input:hidden', row).val($('td:eq(0) > select > option:selected', row).text());
            $('td:eq(2) > input', row).val(productPrice[$('td:eq(0) > select', row).val()]);
            recalculate();
        }

        function editSelect() {
            $('select.editable-select:visible').editableSelect(
            {
                onSelect: function() {
                    var price = $('option:contains(' + this.text.val() + ')', this.select).attr('id');
                    $('input[name="price"]', $(this.select).parents('tr')).val(price);
                    recalculate();
                },
                zIndex: 200,
                case_sensitive: false, // If set to true, the user has to type in an exact
                // match for the item to get highlighted
                items_then_scroll: 10 // If there are more than 10 items, display a scrollbar
            });
        }

        $(document).ready(function(){
            recalculate();
            editSelect();
        });
    </script>
</div>