<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<br><br>
<div class="container">

    <?php echo alerts(); ?>

    <form class="form-horizontal" role="form" method="post">
        <div class="form-group<?php echo (form_error('name') ? ' has-error' : ''); ?>">
            <label for="inputName" class="col-sm-2 control-label">Naam</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="inputName" value="<?php echo $user->name; ?>">
                <?php if (form_error('name')): ?>
                    <span class="help-block">Uw naam moet ingevoerd worden en mag enkel uit letters en spaties bestaan.</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-5">
                <p class="form-control-static"><?php echo $user->email; ?></p>
            </div>
        </div>
        <div class="form-group<?php echo (form_error('password') ? ' has-error' : ''); ?>">
            <label for="inputPassword" class="col-sm-2 control-label">Wachtwoord</label>
            <div class="col-sm-5">
                <input type="password" name="password" class="form-control" id="inputPassword"> 
                <?php if (form_error('password')): ?>
                    <span class="help-block">Een wachtwoord dient minimaal 5 karakters te bevatten, wij raden combinaties van letters, cijfers en tekens aan.</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-primary">Wijzigingen doorvoeren</button>
            </div>
        </div>
    </form>
</div>