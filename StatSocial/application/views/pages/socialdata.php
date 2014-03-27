<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
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