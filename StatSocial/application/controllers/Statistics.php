<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
        if ($this->auth->is() !== TRUE)
        {
            redirect('/');
        }
        
        $this->load->model('statistic_model');
    }
    
    public function index()
    {
        $data['statistics'] = $this->statistic_model->get_table();
        $data['logger']     = $this->logger->last('NDW');
        
		$this->addView('pages/statistics', $data);
		$this->viewPage("Statistieken");
    }
}

/* End of file statistics.php */
/* Location: ./application/controllers/Statistics.php */