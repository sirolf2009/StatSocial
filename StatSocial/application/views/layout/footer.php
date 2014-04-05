<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
        <footer id="footer">
            <div class="container">
                <p class="text-center">STATSOCIAL &copy; 2014 - Alle rechten voorbehouden.</p>
            </div>
        </footer>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="<?php echo base_url('assets/js/core.js'); ?>"></script>
        <script src="<?php echo base_url('assets/js/application.js'); ?>"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/modules/exporting.js"></script>

		<?php foreach($js as $file):?>
			<script src="<?php echo base_url("assets/js/".$file); ?>"></script>
		<?php endforeach;?>

    </body>
</html>