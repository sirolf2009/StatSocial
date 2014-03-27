<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Socialusers extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
        if ($this->auth->is() !== TRUE)
        {
            redirect('/');
        }
        
        $this->load->model('socialuser_model');
        $this->load->helper('form');
    }
    
    public function index()
    {
        if ($this->input->post('name'))
        {
            $this->socialuser_model->like('social_users.name', $this->input->post('name', TRUE));    
        }
        
        if ($this->input->post('type') && $this->input->post('type') !== 'ALL')
        {
            $this->socialuser_model->where('social_users.type', $this->input->post('type', TRUE));    
        }
        
        if ($this->input->post('status') && $this->input->post('status') !== 'ALL')
        {
            $this->socialuser_model->where('excludes.date IS '. ($this->input->post('status') === 'OPEN' ? 'NULL' : 'NOT NULL'));    
        }
        
        $data['users'] = $this->socialuser_model->get_all();
        
        $this->addJs("application.js");
		$this->addView('pages/socialusers', $data);
		$this->viewPage("Sociale gebruikers");
    }
    
    public function block($type, $id)
    {
        // this is needed to ensure that it's a AJAX request, other request methods are forbidden!
        $this->input->is_ajax_request() OR show_404();
        
        $user = $this->socialuser_model->get(array('social_users.type' => $type, 'social_users.social_id' => $id));
        
        if ($this->input->post('socialuserModalPoster'))
        {
            if ($this->socialuser_model->block(strtoupper($type), $id))
            {
                create_alert("success", "<strong>{$user->name} is succesvol geblokkkeerd.</strong>");
                return;      
            }  
        }
        
        $data['user'] = $user;
        $data['header'] = 'Persoon blokkeren';
        $data['text']   = 'Weet u zeker dat u <strong>'.$user->name.'</strong> wilt blokkeren?<br><br>';
        $data['text']  .= '<i class="text-warning">Hierbij worden tevens alle berichten van deze persoon verwijderd.</i>';
        
        $this->load->view('pages/socialusermodal', $data);
    }
    
    public function unblock($type, $id)
    {
        // this is needed to ensure that it's a AJAX request, other request methods are forbidden!
        $this->input->is_ajax_request() OR show_404();
        
        $user = $this->socialuser_model->get(array('social_users.type' => $type, 'social_users.social_id' => $id));
        
        if ($this->input->post('socialuserModalPoster'))
        {
            if ($this->socialuser_model->unblock(strtoupper($type), $id))
            {
                create_alert("success", "<strong>{$user->name} is succesvol gedeblokkkeerd.</strong>");
                return;  
            }
               
        }
        
        $data['user'] = $user;
        $data['header'] = 'Persoon deblokkeren';
        $data['text']   = 'Weet u zeker dat u <strong>'.$user->name.'</strong> wilt deblokkeren?<br><br>';
        
        $this->load->view('pages/socialusermodal', $data);
    }
}

/* End of file socialusers.php */
/* Location: ./application/controllers/Socialusers.php */