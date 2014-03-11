<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

class MY_Model extends CI_Model {
    
    protected $table = NULL;
    protected $primary_key = 'id';
    protected $fields = array();
    protected $validate = array();
    protected $skip_validation = FALSE;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __call($method, $params)
    {
        if (method_exists($this->db, $method))
        {
            call_user_func_array(array($this->db, $method), $params);
            return $this;
        }
    }
    
    public function get()
    {
        $this->_set_where(func_get_args());
        return $this->db->get($this->_table())->row();
    }
    
    public function get_all()
    {
        $this->_set_where(func_get_args());
        return $this->db->get($this->_table())->result();
    }
    
    public function insert($data, $skip_validation = FALSE)
    {
        $valid = TRUE;
        
        if ($skip_validation === FALSE)
        {
            $valid = $this->_run_validation($data);
        }
        
        if ($valid)
        {
            $data = array_intersect_key($data, array_flip($this->_fields()));
            $this->db->insert($this->_table(), $data);
            return $this->db->insert_id();
        }
        
        return FALSE;
    }
    
    public function update($primary_value, $data, $skip_validation = FALSE)
    {
        $valid = TRUE;
        
        if ($skip_validation === FALSE)
        {
            $valid = $this->_run_validation($data);
        }
        
        if ($valid)
        {
            $data = array_intersect_key($data, array_flip($this->_fields()));
            $this->db->where($this->primary_key, $primary_value)->set($data)->update($this->_table());
            return $this->db->affected_rows();
        }
        
        return FALSE;
    }
    
    public function delete()
    {
        $this->_set_where(func_get_args());
        $this->db->delete($this->_table());
        return $this->db->affected_rows();
    }
    
    public function validate($bool = TRUE)
    {
        $this->skip_validaiton = $bool;
        return $this;
    }
    
    private function _run_validation($data)
    {
        if ($this->skip_validation)
        {
            return TRUE;
        }
        
        if ( ! empty($this->validate))
        {
            foreach ($data as $key => $val)
            {
                $_POST[$key] = $val;    
            }
            
            $this->load->library('form_validation');
            
            if (is_array($this->validate))
            {
                $this->form_validation->set_rules($this->validate);
                
                return $this->form_validation->run();
            }
            else
            {
                return $this->form_validation->run($this->validate);
            }
        }
        
        return TRUE;    
    }
    
    private function _set_where($params)
    {
        if (count($params) == 1)
        {
            if ( ! is_array($params[0]) && ! strstr($params[0], "'"))
            {
                $this->db->where($this->primary_key, $params[0]);
            }
            else
            {
                $this->db->where($params[0]);
            }
        }
        elseif (count($params) == 2)
        {
            if (is_array($params[1]))
            {
                $this->db->where_in($params[0], $params[1]);
            }
            else
            {
                $this->db->where($params[0], $params[1]);
            }
        }
    }
    
    private function _fields()
    {
        if ($this->_table() && empty($this->fields))
        {
            $this->fields = $this->db->list_fields($this->_table());
        }
        
        return $this->fields;
    }
    
    private function _table()
    {
        if ($this->table == NULL)
        {
            $this->load->helper('inflector');
            $class = preg_replace('#((_m|_model)$|$(m_))?#', '', strtolower(get_class($this)));
            $this->table = plural(strtolower($class));
        }
        
        return $this->table;
    }
}