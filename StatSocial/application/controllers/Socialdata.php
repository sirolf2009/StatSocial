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
<<<<<<< HEAD
        $data['posts'] = $this->post_model->get_all();
=======
<<<<<<< HEAD
        //$this->post_model->twitter("file A6");
>>>>>>> 9cf466cc9966727c44b1ba0f1f412d1bc2291205
        
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/socialdata', $data);
        $this->load->view('layout/footer', array());
=======
        $this->post_model->facebook("");

		$this->addView('pages/socialdata');
		$this->viewPage('Socialdata');
>>>>>>> 03c864bb6cbbff6bd142e36add875b23b4e9a8ab
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