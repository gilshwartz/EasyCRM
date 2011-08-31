<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>Reports</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
            *{
                margin:0;
                padding:0;
                list-style-type:none;
            }

            html{
                font-size:95%;
            }

            body{
                background:#fff;
                font-family:Arial, Helvetica, sans-serif;
                color:#333;
            }

            #container{
                width:1200px;
                margin:auto;
            }

            .logo{
                float:left;
            }

            /**/

            h1{
                margin:0 0 20px 0;
                font-size:1.5em;
                font-weight:normal;
            }

            table{
                border:1px solid #b3ddf6;
                width:100%;
            }

            table thead th{
                background:#ecf6fc;
                padding:10px 0 10px 0;
                text-align:center;
                border-top:1px solid #fff;
            }

            table td{
                padding:7px 0 7px 0;
                text-indent:7px;
                text-align:center;
                font-size:1em;
            }

            tr.alt td{
                background:#f9f9f9;
            }

            .clearboth{
                clear:both;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <h1 class="clearboth">List of newsletter contacts</h1>

            <table cellpadding="0" cellspacing="0" class="details stripeme">
                <thead>
                    <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Company</th>
                        <th>Country</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Language</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($results['Contacts'] as $contact) {
                        $emails = unserialize($contact['Contact']['emails']);
                        if ($emails === false) {
                            $emails[0] = $contact['Contact']['emails'];
                            $emails[1] = "";
                        }

                        $phones = unserialize($contact['Contact']['phones']);
                        if ($phones === false) {
                            $phones[0] = $contact['Contact']['phones'];
                            $phones[1] = "";
                        }
                        if ($i%2 == 0)
                            echo '<tr>';
                        else
                            echo '<tr class="alt">';
                        echo '<td>' . $contact['Contact']['firstname'] . '</td>';
                        echo '<td>' . $contact['Contact']['lastname'] . '</td>';
                        echo '<td>' . $contact['Contact']['company_name'] . '</td>';
                        echo '<td>' . $results['Countries'][$contact['Contact']['country']] . '</td>';
                        echo '<td>' . $emails[0] . '</td>';
                        echo '<td>' . $phones[0] . '</td>';
                        echo '<td>' . $contact['Contact']['language'] . '</td>';
                        echo '</tr>';
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
            <p class="clearboth" style="padding:30px 0 0 0"></p>
        </div>
    </body>
</html>