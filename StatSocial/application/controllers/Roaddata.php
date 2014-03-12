<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Roaddata extends CI_Controller {
    
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
        $this->load->view('layout/header', array('title' => 'Wegendata'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/roaddata', array());
        $this->load->view('layout/footer', array());
    }
}

/* End of file roaddata.php */
/* Location: ./application/controllers/Roaddata.php */