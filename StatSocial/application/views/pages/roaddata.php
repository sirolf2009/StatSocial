<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <?php echo alerts(); ?>
    <?php echo alert("warning", "Deze weg data is nog niet actueel en volledig!", FALSE); ?>
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