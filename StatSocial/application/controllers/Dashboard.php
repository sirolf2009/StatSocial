<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        if ($this->auth->is() !== TRUE)
        {
            redirect('/');
        }
    }
    
    public function index()
    {
		$this->addView('pages/dashboard', array());
		$this->viewPage("Dashboard");
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/Dashboard.php */