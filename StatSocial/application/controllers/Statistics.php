<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends MY_Controller {

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
        $this->load->library('sentiment');
        
<<<<<<< HEAD
        var_dump($this->sentiment->analyse("Bah! Alweer file bij de A15 ter hoogte van Ridderkerk, #laatophetwerk!", TRUE));
        
        $this->load->view('layout/header', array('title' => 'Statistieken'));
        $this->load->view('layout/nav', array());
        $this->load->view('pages/statistics', array());
        $this->load->view('layout/footer', array());
=======
        var_dump($this->sentiment->analyse("Echt ongelofelijk! Bus rijd voor mijn neus weg!"));

		$this->addView('pages/statistics');
		$this->viewPage("Statistieken");
>>>>>>> 9cf466cc9966727c44b1ba0f1f412d1bc2291205
    }
}

/* End of file statistics.php */
/* Location: ./application/controllers/Statistics.php */