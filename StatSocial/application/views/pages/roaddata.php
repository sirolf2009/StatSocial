<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>

<?php if ( ! $this->input->is_ajax_request()): ?>
<div class="container">

	<?php echo alerts(); ?>

	<ul class="nav nav-tabs">
		<li class="pull-right"><a href="#data" data-toggle="tab">Gegevens</a></li>
        <li class="pull-right active"><a href="#stat" data-toggle="tab">Statestieken</a></li>
        <?php if ( ! empty($logger)): ?>
        <li><i class="glyphicon glyphicon-flag"></i> Laatste gegevens opgehaald op: <?php echo date('d-m-Y \o\m H:i', $logger->date); ?></li>
        <?php endif; ?>
	</ul>
    <br>
	<div class="tab-content">
		<div class="tab-pane active" id="stat">
			<br>
			<div class="row">
				<div class="col-sm-2">
					<ul class="nav nav-pills nav-stacked">
						<li class="active"><a href="#files" data-toggle="tab">Files per weg</a></li>
						<li><a href="#cause" data-toggle="tab">File oorzaken</a></li>
					</ul>
					<br><br>
				</div>
				<div class="tab-content col-sm-10">
					<div class="tab-pane active" id="files">
						<div id="container" style="width:100%; height:700px;"></div>
					</div>
					<div class="tab-pane" id="cause">
						<div id="container_pie" style="width:55%; height:500px;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="data">
			<div class="row">
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Gegevens filteren</div> 
                        <div class="panel-body">
                            <form method="POST" role="form" data-type="ajax-form" action="<?php echo current_url(); ?>">
                                <div class="form-group">
                                    <select class="form-control" name="roadnumber">
                                        <option value="ALL">Alles</option>
                                        <?php foreach ($roads AS $road): ?>
                                            <option value="<?php echo $road; ?>" <?php echo set_select('roadnumber', $road); ?>><?php echo $road; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="type">
                                        <option value="ALL">Alles</option>
                                        <option value="unknown" <?php echo set_select('type', 'unknown'); ?>>Onbekend</option>
                                        <option value="accident" <?php echo set_select('type', 'accident'); ?>>Ongeval(len)</option>
                                        <option value="rubberNecking" <?php echo set_select('type', 'rubberNecking'); ?>>Kijkersfile</option>
                                        <option value="earlierAccident" <?php echo set_select('type', 'earlierAccident'); ?>>Eerder ongeval</option>
                                        <option value="other" <?php echo set_select('type', 'other'); ?>>Overige</option>
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
                    <table class="table" id="ajax-table">
                        <thead>
                            <tr>
                                <th width="10%">Weg</th>
                                <th width="20%">Extra naam</th>
                                <th width="30%">Oorzaak</th>
                                <th width="20%">Start datum</th>
                                <th width="20%">Eind datum</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php endif; ?>

                        <?php if (count($roadData) > 0): ?>
                            <?php foreach ($roadData as $data): ?>
                            <tr>
                                <td><?php echo $data['roadnumber']; ?></td>
                                <td><?php echo $data['roadname']; ?></td>
                                <td><?php echo (empty($data['description']) ? 'Onbekend' : $data['description']); ?></td>
                                <td><?php echo date('d-m-Y \o\m H:i', $data['start_date']); ?></td>
                                <td><?php echo date('d-m-Y \o\m H:i', $data['end_date']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <?php echo alert('warning', 'Helaas is er voor de gevraagde filter geen resultaat gevonden.', FALSE); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                            
    <?php if ( ! $this->input->is_ajax_request()): ?>
                        </tbody>
                    </table>
                </div>
		    </div>
	    </div>
    </div>
</div>
<?php endif; ?>