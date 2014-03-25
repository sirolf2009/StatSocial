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
        $data['posts'] = $this->post_model->get_all();
        
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/socialdata', $data);
        $this->load->view('layout/footer', array());
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */