<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

/**
* MY_Model, model class for easy model use.
* 
* @package      Codeigniter
* @subpackage   Core
* @category     Core
* @author       Ditmar Commandeur 
*/
class MY_Model extends CI_Model {
    
    // table name, if not set this class will guess based on model name.
    protected $table = NULL;
    // primary key value, if more then one primary key.. set the first one.
    protected $primary_key = 'id';
    // all the fields used for this model, fields not declared will not be accesable
    protected $fields = array();
    // validation rules based on Codeigniter's Form_Validation library
    protected $validate = array();
    // should we skip validation?
    protected $skip_validation = FALSE;
    
    /**
    * Constructor method, just constructs the CI_Model for now
    * 
    * @returns ::self
    */
    public function __construct()
    {
        parent::__construct();
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Magic method for calling database functions
    * 
    * @param mixed $method
    * @param mixed $params
    * @access public
    * @return $this > MY_Model
    */
    public function __call($method, $params)
    {
        if (method_exists($this->db, $method))
        {
            call_user_func_array(array($this->db, $method), $params);
            return $this;
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Method for getting a row/record
    * 
    * @param mixed (func_get_args)
    * @access public
    * @return object
    */
    public function get()
    {
        $this->db->select($this->_fields(TRUE));
        $this->_set_where(func_get_args());
        return $this->db->get($this->_table())->row();
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Method for getting all the results
    * 
    * @param mixed (func_get_args)
    * @access public
    * @return array (object)
    */
    public function get_all()
    {
        $this->db->select($this->_fields(TRUE));
        $this->_set_where(func_get_args());
        return $this->db->get($this->_table())->result();
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Method for inserting a new row/record
    * 
    * @param mixed $data
    * @param mixed $skip_validation
    * @access public
    * @return int (insert_id) < TRUE
    * @return FALSE
    */
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
    
    // ------------------------------------------------------------------------
    
    /**
    * Method for updating a row/record
    * 
    * @param mixed $primary_value
    * @param mixed $data
    * @param mixed $skip_validation
    * @access public
    * @return int (affected_rows) < TRUE
    * @return boolean FALSE
    */
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
    
    // ------------------------------------------------------------------------
    
    /**
    * Option for deleting a row (model)
    * 
    * @param mixed (func_get_args)
    * @access public
    * @return int (affected_rows)
    */
    public function delete()
    {
        $this->_set_where(func_get_args());
        $this->db->delete($this->_table());
        return $this->db->affected_rows();
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Option for setting the skip_validation value
    * 
    * @param mixed $bool
    * @access public
    * @return $this > MY_Model
    */
    public function validate($bool = TRUE)
    {
        $this->skip_validaiton = $bool;
        return $this;
    }

    // ------------------------------------------------------------------------
    
    /**
    * Validation method based on data submitted
    * checks if the data submitted validates on the rules set
    * 
    * @param mixed $data
    * @access private
    * @returns boolean
    */
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
    
    // ------------------------------------------------------------------------
    
    /**
    * Set where information based on params
    * options can be:
    * - 1 param where(primary_key => $param)
    * - 2 param where(param[0], param[1])
    * - 2 param where(array(param))
    * - 2 param where_in(param[0], param[1])
    * 
    * @param mixed $params
    * @access private
    * @return NULL
    */
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
            if ( ! isset($params[1]))
            {
                $this->db->where($params);
            }
            elseif (is_array($params[1]))
            {
                $this->db->where_in($params[0], $params[1]);
            }
            else
            {
                $this->db->where($params[0], $params[1]);
            }
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Returns all the fields for this model
    * if no fields are set, field information is retrieved from the database
    * 
    * @access private
    * @returns array $fields
    */
    private function _fields($prefix = FALSE)
    {
        if ($this->_table() && empty($this->fields))
        {
            $this->fields = $this->db->list_fields($this->_table());
        }
        
        if ($prefix)
        {
            $fields = array();
        
            foreach ($this->fields AS $key => $val)
            {
                $fields[$key] = $this->_table().'.'.$val;
            }
            
            return $fields;
        }
        
        return $this->fields;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Function for getting the table name,
    * if no table name is set we will gues it based on model name.
    * 
    * @access private
    * @return $table_name
    */
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