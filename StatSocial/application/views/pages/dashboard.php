<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <?php echo alerts(); ?>
    
    <?php echo $highest; ?>  
    
    <div id="logs"></div>
    <br><br> 
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default text-center">
                <div class="panel-heading">Aantal opgeslagen gebruikers</div>
                <div class="panel-body">
                    <strong><?php echo $dashboard['social_users']['amount']; ?></strong>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default text-center">
                <div class="panel-heading">Aantal opgeslagen berichten</div>
                <div class="panel-body">
                    <strong><?php echo $dashboard['posts']['amount']; ?></strong>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default text-center">
                <div class="panel-heading">Aantal opgeslagen gebeurtenissen</div>
                <div class="panel-body">
                    <strong><?php echo $dashboard['ndw']['amount']; ?></strong>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    (function() {
        function looper() {
            if (typeof jQuery=='undefined'){setTimeout(looper, 50);return;}
        
            $(function() {
                Highcharts.setOptions({
                    lang: {
                        weekdays: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"],
                        shortMonths: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sept", "okt", "nov", "dec"]
                    },
                });
                
                $('#logs').highcharts({
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: 'Aantal aanvragen naar externe platformen over tijd'
                    },
                    xAxis: {
                        type: 'datetime',
                        dateTimeLabelFormats: { 
                            month: '%e %b',
                            year: '%b'
                        }
                    },
                    yAxis: {
                        title: 'Aantal aanvragen',
                        min: 0,
                        minTickInterval: 1,
                        minorGridLineWidth: 0,
                        alternateGridColor: null
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>'+ this.series.name + '</b><br/>' + Highcharts.dateFormat('%e %b om %H:%M', this.x) +': '+ this.y;
                        }
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 4,
                            states: {
                                hover: {
                                    lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    series: [{
                        name: 'Aanvragen',
                        data: [
                        <?php foreach ($dashboard['logs'] AS $date => $amount): ?>
                        [<?php echo $date.','.$amount; ?>],
                        <?php endforeach; ?>
                        ]  
                    }]
                }); 
            });         
        }
        looper();
    })();
    </script>
</div>