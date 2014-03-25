<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Socialdata extends MY_Controller {
    
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
        //$this->post_model->twitter("file A6");
        $this->post_model->facebook("");

		$this->addView('pages/socialdata', $data);
		$this->viewPage('Socialdata');
    }

    public function search() {
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $result = $this->post_model->search($this->input->post("searchPlatform"), $this->input->post("searchPerson"), $this->input->post("searchValue"), 1);
        $this->load->view('pages/socialdata', array("searchResult" => $result));
        $this->load->view('layout/footer', array());
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */