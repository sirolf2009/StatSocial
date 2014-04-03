<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>

<div class="container">

	<?php echo alerts(); ?>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#stat" data-toggle="tab">Statestieken</a></li>
		<li><a href="#data" data-toggle="tab">Gegevens</a></li>
	</ul>

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
						<div id="container_pie" style="width:100%; height:500px;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="data">
			<br>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>
							Weg nummer
						</th>
						<th>
							Weg naam
						</th>
						<th>
							Gebeurtenis type
						</th>
						<th>
							Beschrijving
						</th>
						<th>
							Start
						</th>
						<th>
							Stop
						</th>
						<th>
							Datum
						</th>
					</tr>
					<?php foreach($roadData as $data):?>
						<tr>
							<td>
								<?=$data['roadnumber']?>
							</td>
							<td>
								<?=$data['roadname']?>
							</td>
							<td>
								<?=$data['type']?>
							</td>
							<td>
								<?=$data['description']?>
							</td>
							<td>
								<?=date('d-m-Y H:i', $data['start_date'])?>
							</td>
							<td>
								<?=date('d-m-Y H:i', $data['end_date'])?>
							</td>
							<td>
								<?=date('d-m-Y H:i', $data['date'])?>
							</td>
						</tr>
					<?php endforeach; ?>

				</table>
			</div>
		</div>
	</div>






</div>