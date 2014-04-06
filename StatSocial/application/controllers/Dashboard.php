<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    
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
        $highest = $this->statistic_model->get_highest();
        
        $data['highest'] = alert('info', 'De <strong>'.$highest->roadnumber.'</strong> is momenteel de weg met de meest irriterende files.', FALSE);
        $data['dashboard'] = $this->statistic_model->get_dashboard();
        
		$this->addView('pages/dashboard', $data);
		$this->viewPage("Dashboard");
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/Dashboard.php */