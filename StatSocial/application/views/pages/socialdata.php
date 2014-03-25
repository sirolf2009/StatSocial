<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <?php echo alerts(); ?>
        
    <table class="table">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts AS $post): ?>
            <tr>
                <td><?php echo $post->post_id; ?></td>
                <td><?php echo $post->type; ?></td>
                <td><?php echo $post->message; ?></td>
                <td><?php echo date("d-m-Y", $post->date); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo alert("warning", "Helaas kon ik je nog niet voorzien van sociale data, zullen we het later nog eens proberen?", FALSE); ?>
    <form action="/socialdata/search" role="form" method="post">
   		<div class="row">
   			<div class="col-md-2">
    		<label for="searchValue">Platform?</label>
    		<select id="searchPlatformInput" name="searchPlatform" class="form-control">
  				<option>Twitter</option>
  				<option>Facebook</option>
  				<option>Allebij</option>
			</select>
			</div>
			<div class="form-group col-md-2">
    			<label for="searchPerson">Persoon?</label>
    			<input id="searchPersonInput" name="searchPerson" type="text" class="form-control" placeholder="zoeken...">
  			</div>
			<div class="form-group col-md-8">
    			<label for="searchValue">Waar zoek je naar?</label>
    			<input id="searchValueInput" name="searchValue" type="text" class="form-control" placeholder="zoeken...">
  			</div>
		</div>
		<input type="submit" class="btn btn-primary" value="Zoeken">
    </form>
    <br>
	<div class="table-responsive">
    		<?php if(isset($searchResult)) { ?>
    			print("<input type='button' class='btn btn-info pull-left' value='vorige <'>");
    			print("<input type='button' class='btn btn-info pull-right' value='volgende >'>");
				print("<table class='table'>");
    		<?php foreach($searchResult as $data):?>
					<tr>
						<td>
							<?php echo($data->type); ?>
						</td>
						<td>
							<?php echo($data->name); ?>
						</td>
						<td>
							<?php echo($data->message); ?>
						</td>
						<td>
							<?php echo(date('d-m-Y H:i', $data->date)); ?>
						</td>
					</tr>
				<?php endforeach; 
				print("</table>");
    			print("<input type='button' class='btn btn-info pull-left' value='vorige <'>");
    			print("<input type='button' class='btn btn-info pull-right' value='volgende >'>");
			} ?>
	</div>
</div>