<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {
    
    public function index()
    {
        if ($this->auth->is())
        {
            redirect('dashboard');    
        }
        
        $error = FALSE;

        if ($this->input->post('email') && $this->input->post('password'))
        {
            if (($error = $this->auth->login($this->input->post('email'), $this->input->post('password'))) === TRUE)
            {
                create_alert("info", "Welkom terug <strong>{$this->auth->name()}</strong>, je laatste keer was op ".date('d-m-Y', $this->auth->last_login())." om ".date("H:i", $this->auth->last_login()));
                redirect('dashboard');
            }
        }

		$this->addView('pages/account/login', array('error' => $error));
		$this->viewPage("Inloggen");
    }
    
    public function settings()
    {
        if ($this->auth->is() !== TRUE)
        {
            redirect('/');
        }
        
        $this->load->helper('form');
        
        if ($this->input->post())
        {
            if ($this->user_model->update($this->auth->id(), $this->input->post()))
            {
                create_alert('success', 'Account gegevens zijn succesvol aangepast.');   
                redirect('account/settings', 'refresh'); 
            }
            elseif (count($this->form_validation->error_array()) == 0)
            {
                create_alert('warning', 'Er zijn geen wijzigingen geweest in uw gegevens.');
                redirect('account/settings', 'refresh');
            }
        }

		$this->addView('pages/account/settings', array('user' => $this->user_model->get($this->auth->id())));
		$this->viewPage("Gegevens");
    }
    
    public function logout()
    {
        $this->auth->logout();
        redirect('/');
    }
}

/* End of file account.php */
/* Location: ./application/controllers/Account.php */