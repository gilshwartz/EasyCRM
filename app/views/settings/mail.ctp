<?php
    echo $this->element('submenu/settings');
?>
<script type="text/javascript">
setActiveSubmenu("snav6");
</script>
<div id="submenu">
    <a href="#" onclick="loadPage('settings/ogone')">Ogone</a> |
    <span class="active">Email</span> |
    <!--<a href="#" onclick="">Countries</a> |
    <a href="#" onclick="">ACL</a>-->
</div>
<div class="div1000" style="margin-bottom:70px">
    <h1 class="datepicker">Ogone :</h1>
    <div id="datepicker">
      <!--<input type="text" class="input" style="font-size:0.9em;"/>-->
        <button class="button" onclick="ogoneSave();">Save</button>
    </div>
</div>
<div class="div1000" style="margin-bottom:70px">
    <form id="ogone2save" action="#">
        <table cellpadding="0" cellspacing="0" class="stripeme">
            <thead>
                <tr>
                    <th colspan ="2">Config</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mode</td>
                    <td>
                        <select name="data[Setting][OGONE][CONFIG][MODE]"  class="select">
                            <?php
                            if ($ogone['CONFIG']['MODE'] == 'TEST') {
                                echo '<option selected>TEST</option>';
                                echo '<option>PROD</option>';
                            } else {
                                echo '<option>TEST</option>';
                                echo '<option selected>PROD</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>URL Production</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][CONFIG][URL][PROD]" class="editable" value="<?php echo $ogone['CONFIG']['URL']['PROD']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>URL Test</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][CONFIG][URL][TEST]" class="editable" value="<?php echo $ogone['CONFIG']['URL']['TEST']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>SHASIG</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][CONFIG][SHASIG]" class="editable" value="<?php echo $ogone['CONFIG']['SHASIG']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>SHASIG OUT</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][CONFIG][SHASIG_OUT]" class="editable" value="<?php echo $ogone['CONFIG']['SHASIG_OUT']; ?>"/>
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" class="stripeme">
            <thead>
                <tr>
                    <th colspan ="2">Global</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PSPID</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][PSPID]" class="editable" value="<?php echo $ogone['GLOBAL']['PSPID']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>LANGUAGE</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][LANGUAGE]" class="editable" value="<?php echo $ogone['GLOBAL']['LANGUAGE']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>TITLE</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][TITLE]" class="editable" value="<?php echo $ogone['GLOBAL']['TITLE']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>BGCOLOR</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][BGCOLOR]" class="editable" value="<?php echo $ogone['GLOBAL']['BGCOLOR']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>TXTCOLOR</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][TXTCOLOR]" class="editable" value="<?php echo $ogone['GLOBAL']['TXTCOLOR']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>TBLBGCOLOR</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][TBLBGCOLOR]" class="editable" value="<?php echo $ogone['GLOBAL']['TBLBGCOLOR']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>TBLTXTCOLOR</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][TBLTXTCOLOR]" class="editable" value="<?php echo $ogone['GLOBAL']['TBLTXTCOLOR']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>BUTTONBGCOLOR</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][BUTTONBGCOLOR]" class="editable" value="<?php echo $ogone['GLOBAL']['BUTTONBGCOLOR']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>BUTTONTXTCOLOR</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][BUTTONTXTCOLOR]" class="editable" value="<?php echo $ogone['GLOBAL']['BUTTONTXTCOLOR']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>FONTTYPE</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][FONTTYPE]" class="editable" value="<?php echo $ogone['GLOBAL']['FONTTYPE']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>LOGO</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][LOGO]" class="editable" value="<?php echo $ogone['GLOBAL']['LOGO']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>ACCEPTURL</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][ACCEPTURL]" class="editable" value="<?php echo $ogone['GLOBAL']['ACCEPTURL']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>CANCELURL</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][CANCELURL]" class="editable" value="<?php echo $ogone['GLOBAL']['CANCELURL']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>DECLINEURL</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][DECLINEURL]" class="editable" value="<?php echo $ogone['GLOBAL']['DECLINEURL']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>EXCEPTIONURL</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][EXCEPTIONURL]" class="editable" value="<?php echo $ogone['GLOBAL']['EXCEPTIONURL']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>BACKURL</td>
                    <td>
                        <input type="text" name="data[Setting][OGONE][GLOBAL][BACKURL]" class="editable" value="<?php echo $ogone['GLOBAL']['BACKURL']; ?>"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<script language="javascript">
    $(function() {
        $('select.select').selectmenu();
    });
</script>
<?php debug($ogone); ?>