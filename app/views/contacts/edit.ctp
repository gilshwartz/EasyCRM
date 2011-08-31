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
        <?php
        $emails = unserialize($contact['emails']);
        if ($emails === false) {
            $emails[0] = $contact['emails'];
            $emails[1] = "";
        }

        $phones = unserialize($contact['phones']);
        if ($phones === false) {
            $phones[0] = $contact['phones'];
            $phones[1] = "";
        }
        ?>
        <form action="#" id="contact2edit">
            <table cellpadding="0" cellspacing="0" class="details stripeme">
                <tr>
                    <td><strong>Date registered</strong></td>
                    <td><?php echo $contact['registration']; ?></td>
                </tr>
                <tr>
                    <td><strong>First name</strong></td>
                    <td><input type="text" value="<?php echo $contact['firstname']; ?>" class="editable" name="data[Contact][firstname]"/></td>
                </tr>
                <tr>
                    <td><strong>Last name</strong></td>
                    <td><input type="text" value="<?php echo $contact['lastname']; ?>" class="editable" name="data[Contact][lastname]"/></td>
                </tr>
                <tr>
                    <td><strong>Company</strong></td>
                    <td><input type="text" value="<?php echo $contact['company_name']; ?>" class="editable" name="data[Contact][company_name]"/></td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td><input type="text" value="<?php echo $emails[0]; ?>" class="editable" name="data[Contact][email1]"/></td>
                </tr>
                <tr>
                    <td><strong>Email2</strong></td>
                    <td><input type="text" value="<?php echo $emails[1]; ?>" class="editable" name="data[Contact][email2]"/></td>
                </tr>
                <tr>
                    <td><strong>Phone1</strong></td>
                    <td><input type="text" value="<?php echo $phones[0]; ?>" class="editable" name="data[Contact][phone1]"/></td>
                </tr>
                <tr>
                    <td><strong>Phone2</strong></td>
                    <td><input type="text" value="<?php echo $phones[1]; ?>" class="editable" name="data[Contact][phone2]"/></td>
                </tr>
                <tr>
                    <td><strong>Website</strong></td>
                    <td><input type="text" value="<?php echo $contact['url']; ?>" class="editable" name="data[Contact][url]"/></td>
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
                        <select name="data[Contact][language]">
                            <?php
                            foreach ($languages as $language)
                                if ($language == $contact['language'])
                                    echo '<option SELECTED>'.$language.'</option>';
                                else
                                    echo '<option>'.$language.'</option>';
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Title</strong></td>
                    <td><input type="text" value="<?php echo $contact['role']; ?>" class="editable" name="data[Contact][role]"/></td>
                </tr>
                <tr>
                    <td><strong>Position</strong></td>
                    <td><input type="text" value="<?php echo $contact['position']; ?>" class="editable" name="data[Contact][position]"/></td>
                </tr>
                <tr>
                    <td><strong>Newsletter</strong></td>
                    <td>
                        <select name="data[Contact][newsletter]">
                            <?php
                                if ($contact['newsletter']) {
                                    echo '<option value="1" SELECTED>Yes</option>';
                                    echo '<option value="0">No</option>';
                                } else {
                                    echo '<option value="1">Yes</option>';
                                    echo '<option value="0" SELECTED>No</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Community Newsletter</strong></td>
                    <td>
                        <select name="data[Contact][community_newsletter]">
                            <?php
                                if ($contact['community_newsletter']) {
                                    echo '<option value="1" SELECTED>Yes</option>';
                                    echo '<option value="0">No</option>';
                                } else {
                                    echo '<option value="1">Yes</option>';
                                    echo '<option value="0" SELECTED>No</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Webinar Newsletter</strong></td>
                    <td>
                        <select name="data[Contact][webinar_newsletter]">
                            <?php
                                if ($contact['webinar_newsletter']) {
                                    echo '<option value="1" SELECTED>Yes</option>';
                                    echo '<option value="0">No</option>';
                                } else {
                                    echo '<option value="1">Yes</option>';
                                    echo '<option value="0" SELECTED>No</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
