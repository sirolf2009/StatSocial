<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Logger
* 
* This class will log any data given to it.
* 
* @author Ditmar Commandeur
* @copyright 2014
* @category Library
* @version 0.1a
*/
class Logger {
    
    private $db;
    private $table = 'logs';
    private $types = array('FACEBOOK', 'TWITTER', 'NDW');
    
    public function __construct()
    {
        // load the database into variable, we will need it later.
        $this->db =& get_instance()->db;
    }
    
    // -----------------------------------------------------------------------

    public function add($type, $request)
    {
        $type = strtoupper($type);
        
        if ( ! in_array($type, $this->types))
        {
            return FALSE;
        }
        
        $data['request'] = $request;
        $data['type']    = $type;
        $data['date']    = time();
        
        $this->db->insert($this->table, $data);
    }
    
    // -----------------------------------------------------------------------
    
    public function get($type) 
    {
        if ( ! is_null($type))
        {
            $type = strtoupper($type);
        
            if ( ! in_array($type, $this->types))
            {
                return FALSE;
            }   
            
            $this->db->where('type', $type);
        }

        return $this->order_by('date', 'DESC')->get($this->table)->result();
    }
    
    // -----------------------------------------------------------------------
    
    public function last($type)
    {
        $type = strtoupper($type);
        
        if ( ! in_array($type, $this->types))
        {
            return FALSE;
        }        

        return $this->db->where('type', $type)->order_by('date', 'DESC')->get($this->table)->row();    
    }
}