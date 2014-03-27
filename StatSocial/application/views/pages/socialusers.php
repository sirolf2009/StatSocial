<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Gebruikers filteren</div> 
                <div class="panel-body">
                    <form method="POST" role="form">
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
            
            <table class="table" role="table">
                <thead>
                    <tr>
                        <th width="75%">Naam</th>
                        <th width="15%" class="text-center">Type</th>
                        <th width="5%" class="text-center">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users AS $user): ?>
                    <tr>
                        <td><?php echo $user->name; ?></td>
                        <td class="text-center"><?php echo $user->type; ?></td>
                        <td class="text-center"><?php echo (is_null($user->date) ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Geblokkeerd sinds: '.date('d-m-Y', $user->date).'"></i>'); ?></td>
                        <td class="text-center"><a href="<?php echo base_url('socialusers/'.(is_null($user->date) ? 'block' : 'unblock').'/'.$user->type.'/'.$user->social_id); ?>" data-toggle="modal" data-target="#modal"><i class="glyphicon glyphicon-refresh" data-toggle="tooltip" title="Persoon <?php echo (is_null($user->date) ? 'blokkeren' : 'deblokkeren'); ?>."></i></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>   
        </div> 
    </div>
</div>

