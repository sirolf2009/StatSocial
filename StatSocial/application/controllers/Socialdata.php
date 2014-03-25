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
        //$this->post_model->twitter("file A6");
        
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/socialdata', array());
        $this->load->view('layout/footer', array());
    }

    public function search() {
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $result = $this->post_model->search($_POST["searchPlatform"], $_POST["searchPerson"], $_POST["searchValue"], 1);
        $this->load->view('pages/socialdata', array("searchResult" => $result));
        $this->load->view('layout/footer', array());
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */