<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
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
        $this->load->view('layout/header', array('title' => 'Dashboard'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/dashboard', array());
        $this->load->view('layout/footer', array());
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/Dashboard.php */