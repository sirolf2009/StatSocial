<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <nav class="navbar navbar-default" role="navigation"> 
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand"><img src="<?php echo base_url('assets/img/logo_small.png'); ?>" height="100%"></span>
            </div>
            
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li<?php echo is_active('dashboard', 1); ?>><a href="<?php echo base_url('dashboard'); ?>"><b class="glyphicon glyphicon-dashboard"></b> <span class="hidden-sm">Dashboard</span></a></li>
                    <li<?php echo is_active('roaddata', 1); ?>><a href="<?php echo base_url('roaddata'); ?>"><b class="glyphicon glyphicon-road"></b> <span class="hidden-sm">Wegen data</span></a></li>
                    <li<?php echo is_active('socialdata', 1); ?>><a href="<?php echo base_url('socialdata'); ?>"><b class="glyphicon glyphicon-thumbs-up"></b> <span class="hidden-sm">Sociale data</span></a></li>  
                    <li<?php echo is_active('socialusers', 1); ?>><a href="<?php echo base_url('socialusers'); ?>"><b class="glyphicon glyphicon-user"></b> <span class="hidden-sm">Sociale gebruikers</span></a></li>  
                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo is_active('account', 1, array('dropdown')); ?>>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->auth->name(); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li<?php echo is_active('account/settings', 2); ?>><a href="<?php echo base_url('account/settings'); ?>">Gegevens</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo base_url('account/logout'); ?>">Uitloggen</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>                                             
    </nav>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal" aria-labelledby="remoteModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"></div></div></div>