<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

class Cronjob extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // this is needed to ensure that it's a CLI request, other request methods are forbidden!
        //$this->input->is_cli_request() OR show_404();
    }
    
    public function run()
    {
        $this->load->library('sentiment');
        
        $this->load->model('ndw_model');
        $this->load->model('post_model');
        
        // let's start with getting some NDW data
        $this->ndw_model->getActualData();
        
        // get road information from this data 
        $ndw_data = $this->ndw_model->getRecentWithLocation();
                              
        // let's get Twitter data
        foreach ($ndw_data AS $data)
        {
            $this->post_model->twitter($data->roadnumber, $data->id);
        }
        
        // let's get Facebook data
        foreach ($ndw_data AS $data)
        {
            $this->post_model->facebook($data->roadnumber, $data->id);
        }
        
        // set the time limit to inifinite..
        set_time_limit(300);
        
        // last but not least, try to put some sentiment values to the stored posts
        $this->post_model->where('positive', NULL);
        $this->post_model->where('negative', NULL);
        
        foreach ($this->post_model->get_all() AS $i => $post)
        {
            $values = $this->sentiment->analyse($post->message, TRUE);

            $this->post_model->reconnect();
            $this->post_model->update($post->id, $values, TRUE);
        } 
        
        // cleanup duplicates
        $this->post_model->cleanup();
        
        // set the time limit to 30 seconds..
        set_time_limit(30); 
    }    
}