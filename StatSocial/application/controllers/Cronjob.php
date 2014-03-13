<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

class Cronjob extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // this is needed to ensure that it's a CLI request, other request methods are forbidden!
        $this->input->is_cli_request() OR show_404();
    }
    
    public function run()
    {
                
    }    
}