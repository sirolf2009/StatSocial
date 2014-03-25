<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends MY_Controller {

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
        $this->load->library('sentiment');
        $this->addView('pages/statistics');
		$this->viewPage("Statistieken");
    }
}

/* End of file statistics.php */
/* Location: ./application/controllers/Statistics.php */