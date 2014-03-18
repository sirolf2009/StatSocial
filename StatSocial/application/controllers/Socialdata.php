<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Socialdata extends CI_Controller {
    
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
        
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/socialdata', array());
        $this->load->view('layout/footer', array());
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */