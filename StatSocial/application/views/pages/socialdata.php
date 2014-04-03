<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <ul class="nav nav-tabs">
        <li class="pull-right"><a href="#table" data-toggle="tab">Gegevens</a></li>
        <li class="active pull-right"><a href="#charts" data-toggle="tab">Statistieken</a></li>
    </ul><br>
    <div class="tab-content">
        <div class="tab-pane" id="table">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Berichten filteren</div> 
                        <div class="panel-body">
                            <form method="POST" role="form">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Naam plaatser" name="name" value="<?php echo set_value('poster');?>">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="type">
                                        <option value="ALL">Alles</option>
                                        <option value="TWITTER" <?php echo set_select('type', 'TWITTER');?>>Twitter</option>
                                        <option value="FACEBOOK" <?php echo set_select('type', 'FACEBOOK');?>>Facebook</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="date">
                                        <option value="DESC">Nieuwste eerst</option>
                                        <option value="ASC" <?php echo set_select('date', 'ASC');?>>Oudste eerst</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="amount">
                                        <option value="25">Maximaal 25</option>
                                        <option value="50" <?php echo set_select('amount', 50);?>>Maximaal 50</option>
                                        <option value="100" <?php echo set_select('amount', 100);?>>Maximaal 100</option>
                                        <option value="250" <?php echo set_select('amount', 250);?>>Maximaal 250</option>
                                        <option value="500" <?php echo set_select('amount', 500);?>>Maximaal 500</option>
                                        <option value="ALL" <?php echo set_select('amount', 'ALL');?>>Geen maximum</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Vanaf (DD-MM-YYYY)" name="date_from" value="<?php echo set_value('date_from');?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Tot (DD-MM-YYYY)" name="date_to" value="<?php echo set_value('date_to');?>">
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-block">Toepassen</button>     
                            </form>  
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php echo alerts(); ?>
                        
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="60%">Bericht</th>
                                <th width="10%">Medium</th>
                                <th width="15%">Poster</th>
                                <th width="15%">Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts AS $post): ?>
                            <tr>
                                <td><?php echo word_limiter($post->message, 25, '&#8230; <a href="#more" data-toggle="popover" data-title="Volledig bericht" data-content="'.str_replace('"', "&#34;", $post->message).'">Alles lezen</a>'); ?></td>
                                <td><?php echo $post->type; ?></td>
                                <td><?php echo $post->name; ?></td>
                                <td><?php echo date("d-m-Y \o\m H:i", $post->date); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane active" id="charts">
            <div class="row">
                <div class="col-sm-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#charts-tab-1" data-toggle="tab">Aantal berichten</a></li>
                        <li><a href="#charts-tab-2" data-toggle="tab">Berichten over tijd</a></li>
                        <li><a href="#charts-tab-3" data-toggle="tab">Emoties per medium</a></li>
                        <li><a href="#charts-tab-4" data-toggle="tab">Emoties over tijd</a></li>
                    </ul>
                    <br><br>
                </div>
                <div class="tab-content col-sm-10">
                    <div class="tab-pane active" id="charts-tab-1">
                        <div id="pie"></div>
                    </div>
                    <div class="tab-pane" id="charts-tab-2">
                        <div id="posts"></div>
                    </div>
                    <div class="tab-pane" id="charts-tab-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="twitter_donut"></div>
                            </div>
                            <div class="col-sm-6">
                                <div id="facebook_donut"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="charts-tab-4">
                        <div id="sentiment"></div>
                    </div>
                </div>
            </div>
            

            <script type="text/javascript">
            (function() {
                function looper() {
                    if (typeof jQuery=='undefined'){setTimeout(looper, 50);}
                
                    $(function() {
                        $('#pie').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWith: null,
                                plotShadow: false
                            },
                            title: {
                                text: 'Aantal geplaatste berichten per medium.'
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.y}</b>'
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        color: '#000000',
                                        connectorColor: '#000000',
                                        format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                                    }
                                }
                            },
                            series: [{
                                type: 'pie',
                                name: 'Aantal berichten',
                                data: [<?php echo implode(',', $pie_data); ?>]
                            }]
                        }); 

                        Highcharts.setOptions({
                            lang: {
                                weekdays: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"],
                                shortMonths: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sept", "okt", "nov", "dec"]
                            },
                        });
                        
                        $('#posts').highcharts({
                            chart: {
                                type: 'spline'
                            },
                            title: {
                                text: 'Aantal geplaatste berichten per medium over tijd'
                            },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: { 
                                    month: '%e. %b',
                                    year: '%b'
                                }
                            },
                            yAxis: {
                                title: 'Aantal berichten',
                                min: 0,
                                minTickInterval: 1,
                                minorGridLineWidth: 0,
                                alternateGridColor: null
                            },
                            tooltip: {
                                formatter: function() {
                                    return '<b>'+ this.series.name + '</b><br/>' + Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' berichten';
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
                            series: [
                            <?php foreach ($spline_data AS $name => $dates): ?>
                            {
                                name: '<?php echo $name; ?>',
                                data: [
                                <?php foreach ($dates AS $date => $amount): ?>
                                [<?php echo $date.','.$amount; ?>],
                                <?php endforeach; ?>
                                ]  
                            },
                            <?php endforeach; ?>
                            ]
                        }); 
                        
                        $('#twitter_donut').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: 0,
                                plotShadow: false
                            },
                            title: {
                                text: 'Bericht emoties<br>Twitter',
                                align: 'center',
                                verticalAlign: 'middle',
                                y: 50
                            },
                            tooltip: {
                                pointFormat: '<b>{point.percentage:.1f}%</b>'
                            },
                            plotOptions: {
                                pie: {
                                    dateLabels: {
                                        enabled: true,
                                        distance: -50,
                                        style: {
                                            fontWieght: 'bold',
                                            color: 'white',
                                            textShadow: '0px 1px 2px black'
                                        }
                                    },
                                    startAngle: -90,
                                    endAngle: 90
                                }
                            },
                            series: [{
                                type: 'pie',
                                innerSize: '50%',
                                data: [
                                <?php foreach ($twitter_donut AS $type => $value): ?>
                                ['<?php echo $type."',".$value; ?>],
                                <?php endforeach; ?>
                                ]
                            }]
                        });
                        
                        $('#facebook_donut').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: 0,
                                plotShadow: false
                            },
                            title: {
                                text: 'Bericht emoties<br>Facebook',
                                align: 'center',
                                verticalAlign: 'middle',
                                y: 50
                            },
                            tooltip: {
                                pointFormat: '<b>{point.percentage:.1f}%</b>'
                            },
                            plotOptions: {
                                pie: {
                                    dateLabels: {
                                        enabled: true,
                                        distance: -50,
                                        style: {
                                            fontWieght: 'bold',
                                            color: 'white',
                                            textShadow: '0px 1px 2px black'
                                        }
                                    },
                                    startAngle: -90,
                                    endAngle: 90
                                }
                            },
                            series: [{
                                type: 'pie',
                                innerSize: '50%',
                                data: [
                                <?php foreach ($facebook_donut AS $type => $value): ?>
                                ['<?php echo $type."',".$value; ?>],
                                <?php endforeach; ?>
                                ]
                            }]
                        });
                        
                        $('#sentiment').highcharts({
                            chart: {
                                type: 'spline'
                            },
                            title: {
                                text: 'Emotie over tijd'
                            },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: { 
                                    month: '%e. %b',
                                    year: '%b'
                                }
                            },
                            yAxis: {
                                title: 'Aantal berichten',
                                min: 0,
                                minTickInterval: 1,
                                minorGridLineWidth: 0,
                                alternateGridColor: null
                            },
                            tooltip: {
                                formatter: function() {
                                    return '<b>'+ this.series.name + '</b><br/>' + Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' berichten';
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
                            series: [
                            <?php foreach ($sentiment AS $name => $dates): ?>
                            {
                                name: '<?php echo $name; ?>',
                                data: [
                                <?php foreach ($dates AS $date => $amount): ?>
                                [<?php echo $date.','.$amount; ?>],
                                <?php endforeach; ?>
                                ]  
                            },
                            <?php endforeach; ?>
                            ]
                        }); 
                    });         
                }
                looper();
            })();
            </script>
        </div>
    </div>
</div>