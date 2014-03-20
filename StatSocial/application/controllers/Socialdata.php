<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Socialdata extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        if ($this->auth->is() !== TRUE)
        {
            redirect('/');
        }
        
        $this->load->model('post_model');
    }
    
    public function index()
    {
        $this->post_model->facebook("");

		$this->addView('pages/socialdata');
		$this->viewPage('Socialdata');
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */