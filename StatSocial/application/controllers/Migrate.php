<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

/**
* Migration controller
* 
* @package      Codigniter
* @subpackage   Controller
* @category     Migrations
* @author       Ditmar Commandeur
*/
class Migrate extends CI_Controller {
    
    /**
    * Default constructor
    * 
    * loads migration library used in this controller.
    * 
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('migration');
    }
    
    /**
    * Index method, will be called if no method request is given.
    * this method will run the migration to it's latest version.
    * 
    * @return void
    */
    public function index()
    {
        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        }    
    }
}