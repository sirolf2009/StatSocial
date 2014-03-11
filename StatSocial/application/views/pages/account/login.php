<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>

<div class="container">
    <div id="login-form">
        <form method="post" class="form-horizontal" role="form">
            <img src="<?php echo base_url('assets/img/logo_small_blue.png'); ?>" alt="Logo" class="center-block">
            <br>
            <?php echo ( ! $error ? '' : alert('danger', $error, FALSE)); ?>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-user"></span>
                        </span>
                        <input type="email" name="email" class="form-control input-lg" placeholder="email.." value="<?php echo $this->input->post("email"); ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-lock"></span>
                        </span>
                        <input type="password" name="password" class="form-control input-lg" placeholder="wachtwoord..">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Inloggen</button>
                </div>
            </div>
        </form>
    </div>
</div>