<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class User_Model extends MY_Model {
    
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fields = array('id', 'name', 'email', 'password', 'last_login');
    
    public function update($primary_value, $data, $skip_validation = FALSE)
    {
        $this->validate[] = array('field' => 'name', 'label' => 'name', 'rules' => 'trim|required|alpha_numeric_spaces');
        
        if (isset($data['password']) && ! empty($data['password']))
        {
            $this->validate[] = array('field' => 'password', 'label' => 'password', 'rules' => 'trim|required|min_length[5]');    
            $data['password'] = $this->auth->hash($data['password']); 
        }
        else
        {
            unset($data['password']);
        }

        return parent::update($primary_value, $data, $skip_validation);
    }                            
}