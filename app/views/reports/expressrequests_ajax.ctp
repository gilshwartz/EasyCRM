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

<div>
    <h1 class="clearboth">Free Express Planner Downloads</h1>
    <form method="post" action="" id="daterange">
        <b>Select your report interval :</b>
        <input type="text" name="interval" value="<?php echo $interval; ?>" id="date" />
    </form>
    <script>
        $('#date').daterangepicker({
            arrows:true,
            onClose: function() {
                $.ajax({
                    url: 'reports/export/expressrequests',
                    data: $('#daterange').serialize(),
                    type: 'POST',
                    cache: false,
                    success: function(data) {
                        $('#webpage').html(data);
                    }
                });
            }
        });
    </script>
    <p class="clearboth" style="padding:30px 0 0 0"></p>
</div>

<div style="float: left;width: 450px;">
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

<div style="float: right;width: 450px;">
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
<script type='text/javascript'>
    function loadGoogleAPI(){
        google.load('visualization', '1', {'packages': ['geomap'], "callback":drawMap});
        google.load("visualization", "1", {packages:["corechart"], "callback":drawChart});
    }

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

    loadGoogleAPI();
</script>