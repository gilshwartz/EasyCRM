<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form action="#" id="contact2add">
            <table cellpadding="0" cellspacing="0" class="details stripeme">
                <tr>
                    <td><strong>First name</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][firstname]"/></td>
                </tr>
                <tr>
                    <td><strong>Last name</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][lastname]"/></td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][email1]"/></td>
                </tr>
                <tr>
                    <td><strong>Email2</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][email2]"/></td>
                </tr>
                <tr>
                    <td><strong>Phone1</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][phone1]"/></td>
                </tr>
                <tr>
                    <td><strong>Phone2</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][phone2]"/></td>
                </tr>
                <tr>
                    <td><strong>Website</strong></td>
                    <td><input type="text" class="editable" name="data[Contact][url]"/></td>
                </tr>
                <tr>
                    <td><strong>Country</strong></td>
                    <td>
                        <select name="data[Contact][country]">
                            <?php
                            foreach ($countries as $key => $value)
                                if ($key == $contact['country'])
                                    echo '<option value="'.$key.'" SELECTED>'.$value.'</option>';
                                else
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Language</strong></td>
                    <td>
                        <select  name="data[Contact][language]">
                            <option value="">-- Choose --</option>
                            <?php
                            foreach ($languages as $language)
                                echo '<option>'.$language.'</option>';
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Title</strong></td>
                    <td><input type="text" value="" class="editable"  name="data[Contact][role]"/></td>
                </tr>
                <tr>
                    <td><strong>Position</strong></td>
                    <td><input type="text" value="" class="editable"  name="data[Contact][position]"/></td>
                </tr>
            </table>
        </form>
    </body>
</html>
