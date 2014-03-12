<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends CI_Controller {
    
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
        $this->load->view('layout/header', array('title' => 'Statistieken'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/statistics', array());
        $this->load->view('layout/footer', array());
    }
}

/* End of file statistics.php */
/* Location: ./application/controllers/Statistics.php */