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
        $this->load->helper('form');
        $this->load->helper('text');
    }
    
    public function index()
    {
        $order_by = 'DESC';
        $limit    = 25;
        
        if ($this->input->post('name'))
        {
            $this->post_model->like('social_users.name', $this->input->post('name', TRUE));    
        }
        
        if ($this->input->post('type') && $this->input->post('type') !== 'ALL')
        {
            $this->post_model->where('posts.type', $this->input->post('type', TRUE));    
        }
        
        if ($this->input->post('date_from'))
        {
            $this->post_model->where('posts.date >=', strtotime($this->input->post('date_from', TRUE)));    
        }
        
        if ($this->input->post('date_to'))
        {
            $this->post_model->where('posts.date <=', strtotime($this->input->post('date_to', TRUE)) + 86399);   
        }
        
        if ($this->input->post('date'))
        {
            $order_by = $this->input->post('date');  
        }
        
        if ($this->input->post('amount'))
        {
            $limit = ($this->input->post('amount') !== 'ALL' ? $this->input->post('amount') : 99999999);    
        }
        
                         $this->post_model->limit($limit);
                         $this->post_model->order_by("posts.date", $order_by);                 
        $data['posts'] = $this->post_model->get_all();
        
        $data['pie_data']       = $this->post_model->pie();
        $data['spline_data']    = $this->post_model->spline(strtotime('-2 month'), time());
        $data['twitter_donut']  = $this->post_model->donut('TWITTER');
        $data['facebook_donut'] = $this->post_model->donut('FACEBOOK');
        $data['sentiment']      = $this->post_model->sentiment(strtotime('-2 month'), time());

		$this->addView('pages/socialdata', $data);
		$this->viewPage('Sociale data');
    }
}

/* End of file socialdata.php */
/* Location: ./application/controllers/Socialdata.php */