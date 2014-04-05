<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>

<?php if ( ! $this->input->is_ajax_request()): ?>
<div class="container">
    <ul class="nav nav-tabs">
        <li class="pull-right"><a href="#table" data-toggle="tab">Gegevens</a></li>
        <li class="active pull-right"><a href="#charts" data-toggle="tab">Statistieken</a></li>
    </ul><br>
    <div class="tab-content">
        <div class="tab-pane" id="table" data-type="ajax-form">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Gebruikers filteren</div> 
                        <div class="panel-body">
                            <form method="POST" role="form" data-type="ajax-form" action="<?php echo current_url(); ?>">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Naam" name="name" value="<?php echo set_value('name');?>">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="type">
                                        <option value="ALL">Alles</option>
                                        <option value="TWITTER" <?php echo set_select('type', 'TWITTER');?>>Twitter</option>
                                        <option value="FACEBOOK" <?php echo set_select('type', 'FACEBOOK');?>>Facebook</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="ALL">Alles</option>
                                        <option value="OPEN" <?php echo set_select('status', 'OPEN');?>>Actief</option>
                                        <option value="BLOCKED" <?php echo set_select('status', 'BLOCKED');?>>Geblokkeerd</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-block">Toepassen</button>     
                            </form>  
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php echo alerts(); ?>
                    
                    <table class="table" role="table" id="ajax-table">
                        <thead>
                            <tr>
                                <th width="75%">Naam</th>
                                <th width="15%" class="text-center">Type</th>
                                <th width="5%" class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
<?php endif; ?>
                        
                            <?php foreach ($users AS $user): ?>
                            <tr>
                                <td><?php echo $user->name; ?></td>
                                <td class="text-center"><?php echo $user->type; ?></td>
                                <td class="text-center"><?php echo (is_null($user->date) ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Geblokkeerd sinds: '.date('d-m-Y', $user->date).'"></i>'); ?></td>
                                <td class="text-center"><a href="<?php echo base_url('socialusers/'.(is_null($user->date) ? 'block' : 'unblock').'/'.$user->type.'/'.$user->social_id); ?>" data-toggle="modal" data-target="#modal"><i class="glyphicon glyphicon-refresh" data-toggle="tooltip" title="Persoon <?php echo (is_null($user->date) ? 'blokkeren' : 'deblokkeren'); ?>."></i></a></td>
                            </tr>
                            <?php endforeach; ?>
                            
<?php if ( ! $this->input->is_ajax_request()): ?>
                        </tbody>
                    </table>   
                </div> 
            </div>
        </div>
        <div class="tab-pane active" id="charts">
            <div id="chart"></div>

            <script type="text/javascript">
            (function() {
                function looper() {
                    if (typeof jQuery=='undefined'){setTimeout(looper, 50);}
                
                    $(function() {
                        $('#chart').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWith: null,
                                plotShadow: false
                            },
                            title: {
                                text: 'Sociale gebruikers per medium.'
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
                                name: 'Aantal gebruikers',
                                data: [<?php echo implode(',', $chart_data); ?>]
                            }]
                        }); 
                    });         
                }
                looper();
            })();
            </script>
        </div>
    </div>
</div>
<?php endif; ?>