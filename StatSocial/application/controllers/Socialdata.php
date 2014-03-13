<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Socialdata extends CI_Controller {
    
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
        $this->load->driver('request');
        
        // twitter voorbeeld
        // $this->request->select_driver('twitter');
        // $this->request->get(array('q' => 'file A2', 'result_type' => 'recent', 'lang' => 'nl'))
        
        // facebook voorbeeld
        // $this->request->select_driver('facebook');
        // $this->request->get(array('q' => 'file A2', 'type' => 'post'));
        
        // ndw voorbeeld
        //$this->request->select_driver('ndw');
        // $this->request->get();
        
        $this->load->view('layout/header', array('title' => 'Socialdata'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/socialdata', array());
        $this->load->view('layout/footer', array());
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */