<?php
$countries = array();
foreach ($results['Country'] as $tmp) {
    if ($tmp['p']['name'] != '' && $tmp['p']['name'] != 'UNKNOWN') {
        if ($tmp['p']['name'] != 'Russia (Russian Federation)')
            $countries[$tmp['p']['name']] = $tmp[0]['count'];
        else
            $countries['Russia'] = $tmp[0]['count'];
    }
}

$dates = array();
foreach ($results['Date'] as $tmp) {
    $dates[$tmp[0]['date']] = $tmp[0]['count'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>Reports</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        $css = array(
            'jquery-ui',
            'ui.daterangepicker.css'
        );
        $js1 = array(
            'jquery',
            'jquery-ui',
            'daterangepicker.jQuery.js'
        );
        echo $html->css($css);
        echo $html->script($js1);
        ?>
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

            h2{
                margin:0 0 20px 0;
                font-size:1.2em;
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

            .date {
                float: left;
                width: 500px;
            }

            .country {
                float: right;
                width: 500px;
            }
        </style>
        <script type='text/javascript' src='http://www.google.com/jsapi?key=ABQIAAAAzxapLamB-u8IXkQLkxlTmRTuim4sXuH7lN7ENXgXh2OQBJXaPhR0Po9RAtqf7m1X0zXDWDxIQEUXJw'></script>
        <script type='text/javascript'>
            google.load('visualization', '1', {'packages': ['geomap']});
            google.setOnLoadCallback(drawMap);

            function drawMap() {
                var data = new google.visualization.DataTable();
                data.addRows(<?php echo count($countries); ?>);
                data.addColumn('string', 'Country');
                data.addColumn('number', 'Trials');
                <?php
                $i = 0;
                foreach ($countries as $key => $value) {
                    echo "data.setValue($i, 0, '$key');\n";
                    echo "data.setValue($i, 1, $value);\n";
                    $i++;
                }
                ?>

                var options = {};
                options['dataMode'] = 'regions';
                options['width'] = '500';
                options['height'] = '250';

                var container = document.getElementById('map_canvas');
                var geomap = new google.visualization.GeoMap(container);
                geomap.draw(data, options);
            };

            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Date');
                data.addColumn('number', 'Trials');
                data.addRows(<?php echo count($dates); ?>);
                <?php
                $i = 0;
                foreach ($dates as $key => $value) {
                    echo "data.setValue($i, 0, '$key');\n";
                    echo "data.setValue($i, 1, $value);\n";
                    $i++;
                }
                ?>

                var options = {};
                options['width'] = '500';
                options['height'] = '250';
                options['vAxis'] = {minValue: 0};

                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                chart.draw(data, options);
              }
        $(function(){
              $('#datepicker').daterangepicker({
                  arrows:true,
                  onClose: function() {
                      $('#daterange').submit();
                  }
              });
        });
        </script>
    </head>
    <body>
        <div id="container">
            <div>
                <h1 class="clearboth">Trial Requests</h1>
                <form method="post" action="" id="daterange">
                    <b>Select your report interval :</b>
                    <input type="text" name="interval" value="<?php echo $interval;?>" id="datepicker" />
                </form>
                <p class="clearboth" style="padding:30px 0 0 0"></p>
            </div>
            <div>
                <h2 class="clearboth">Requests by Product</h2>
                <table cellpadding="0" cellspacing="0" class="details stripeme">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Trial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($results['Products'] as $tmp) {
                            echo '<tr>';
                            echo '<td>' . $tmp['t']['product'] . '</td>';
                            echo '<td>' . $tmp[0]['count'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <p class="clearboth" style="padding:30px 0 0 0"></p>
            </div>

            <div class="date">
                <h2 class="clearboth">Requests by Date</h2>

                <div id="chart_div"></div>
                <p class="clearboth" style="padding:30px 0 0 0"></p>

                <table cellpadding="0" cellspacing="0" class="details stripeme">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Trial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($results['Date'] as $tmp) {
                            echo '<tr>';
                            echo '<td>' . $tmp[0]['date'] . '</td>';
                            echo '<td>' . $tmp[0]['count'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="country">
                <h2 class="clearboth">Requests by Country</h2>

                <div id='map_canvas' class="clearboth"></div>
                <p class="clearboth" style="padding:30px 0 0 0"></p>

                <table cellpadding="0" cellspacing="0" class="details stripeme">
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Trials</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $unknown = 0;
                        foreach ($results['Country'] as $tmp) {
                            if ($tmp['p']['name'] != '' && $tmp['p']['name'] != 'UNKNOWN') {
                                echo '<tr>';
                                echo '<td>' . $tmp['p']['name'] . '</td>';
                                echo '<td>' . $tmp[0]['count'] . '</td>';
                                echo '</tr>';
                            } else {
                                $unknown = $unknown + $tmp[0]['count'];
                            }
                        }
                        if ($unknown > 0) {
                            echo '<tr>';
                            echo '<td>UNKNOWN</td>';
                            echo '<td>' . $unknown . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>


            <p class="clearboth" style="padding:30px 0 0 0"></p>
        </div>
    </body>

</html>