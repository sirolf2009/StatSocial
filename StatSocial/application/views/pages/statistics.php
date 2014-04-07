<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <?php echo alerts(); ?>
    
    <ul class="nav nav-tabs">
        <li class="pull-right"><a href="#data" data-toggle="tab"><b class="glyphicon glyphicon-list-alt"></b> Gegevens</a></li>
        <li class="pull-right active"><a href="#stat" data-toggle="tab"><b class="glyphicon glyphicon-stats"></b> Statistieken</a></li>
        <?php if ( ! empty($logger)): ?>
        <li><i class="glyphicon glyphicon-flag"></i> Laatste gegevens opgehaald op: <?php echo date('d-m-Y \o\m H:i', $logger->date); ?></li>
        <?php endif; ?>
    </ul>
    <br>
    <div class="tab-content">
        <div class="tab-pane active" id="stat">
            <div id="container" style="width:100%; height:700px;"></div>
        </div>
        <div class="tab-pane" id="data">
            <table class="table" role="table">
                <thead>
                    <tr>
                        <th class="text-center">Weg</th>
                        <th class="text-center">Aantal gebeurtenissen</th>
                        <th class="text-center">Aantal berichten</th>
                        <th class="text-center">Negatieve waarde</th>
                        <th class="text-center">Eindscore</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statistics AS $i => $statistic): ?>
                    <tr<?php echo ($i < 1 ? ' class="active" style="font-weight:bold;"' : ''); ?>>
                        <td class="text-center"><?php echo $statistic->roadnumber; ?></td>
                        <td class="text-center"><?php echo $statistic->events; ?></td>
                        <td class="text-center"><?php echo $statistic->amount; ?></td>
                        <td class="text-center"><?php echo round($statistic->sentiment, 0); ?></td>
                        <td class="text-center"><?php echo number_format($statistic->finalscore, 3, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>  
        </div>  
    </div>
    
    <script type="text/javascript">
    (function() {
        function looper() {
            if (typeof jQuery=='undefined'){setTimeout(looper, 50);return;}
            
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Eindscore per weg'
                },
                xAxis: {
                    categories: [<?php foreach ($statistics AS $statistic): echo "'".$statistic->roadnumber."', "; endforeach; ?>]
                },
                yAxis: {
                    title: {
                        text: 'Score'
                    }
                },
                series: [{
                        name: 'Totaal',
                        data: [<?php foreach ($statistics AS $statistic): echo $statistic->finalscore.', '; endforeach; ?>]
                    }]
            });        
        }
        looper();
    })();
    </script>
</div>