<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<form role="form" method="post" data-async action="<?php echo base_url('socialusers/'.(is_null($user->date) ? 'block' : 'unblock').'/'.$user->type.'/'.$user->social_id); ?>">
    <input type="hidden" name="socialuserModalPoster" value="go">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="remoteModalLabel"><?php echo $header; ?></h4>
    </div>  
    <div class="modal-body">  
        <p><?php echo $text; ?></p>
    </div>  
    <div class="modal-footer">  
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
        <button type="submit" class="btn btn-primary">Doorvoeren</button>
    </div>  
</form>