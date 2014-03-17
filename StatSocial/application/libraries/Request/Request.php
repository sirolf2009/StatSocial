<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

/**
 * Request class
 *
 * @package        CodeIgniter
 * @subpackage     Libraries
 * @category       Requests
 * @author         Ditmar Commandeur
 */
class CI_Request extends CI_Driver_Library {

    /**
     * Valid drivers list
     *
     * @var    array
     */
    protected $valid_drivers = array('facebook', 'twitter', 'ndw', 'alchemy');

    /**
     * Initialization parameters
     *
     * @var    array
     */
    protected $params = array();
    
    /**
     * Current driver in use
     *
     * @var    string
     */
    protected $current = NULL;

    /**
     * Request data
     *
     * @var    array
     */
    protected $requestdata = array();   
    
    // ------------------------------------------------------------------------
    
    /**
     * Request constructor
     *
     * The constructor loads the configured driver ('request_driver' in config.php or as a parameter), running
     * routines in its constructor.
     *
     * @param    array    Configuration parameters
     * @return    void
     */
    public function __construct(array $params = array())
    {
        $_config =& get_instance()->config;
        
        log_message('debug', 'Request Class Initialized');
        
        // Add possible extra entries to our valid drivers list
        $drivers = isset($params['request_valid_drivers']) ? $params['request_valid_drivers'] : $_config->item('request_valid_drivers');
        if ( ! empty($drivers))
        {
            $drivers = array_map('strtolower', (array) $drivers);
            $this->valid_drivers = array_merge($this->valid_drivers, array_diff($drivers, $this->valid_drivers));    
        }
        
        // Get driver to load
        $driver = isset($params['request_driver']) ? $params['request_driver'] : $_config->item('request_driver');
        if ( ! $driver)
        {
            log_message('error', 'Request: No driver name is configured or set.');
            return;
        }
        
        if ( ! in_array($driver, $this->valid_drivers))
        {
            log_message('error', 'Request: Configured driver name is not valid, aborting.');
            return;
        }
        
        // Save a copy of parameters in case drivers need access
        $this->params = $params;

        // Load driver and get array reference
        $this->load_driver($driver);
        
        log_message('debug', 'Request routines successfully run');
    }
    
    // ------------------------------------------------------------------------

    /**
     * Loads request driver
     *
     * @param    string    Driver classname
     * @return    object    Loaded driver object
     */
    public function load_driver($driver)
    {
        // Save reference to most recently loaded driver as library default and sync userdata
        $this->current = parent::load_driver($driver);
        //$this->userdata =& $this->current->get_userdata();
        return $this->current;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Select default request driver
     *
     * @param    string    Driver name
     * @return    void
     */
    public function select_driver($driver)
    {
        // Validate driver name
        $prefix = (string) get_instance()->config->item('subclass_prefix');
        $child = strtolower(str_replace(array('CI_', $prefix, $this->lib_name.'_'), '', $driver));
        if (in_array($child, array_map('strtolower', $this->valid_drivers)))
        {
            // See if driver is loaded
            if (isset($this->$child))
            {
                // See if driver is already current
                if ($this->$child !== $this->current)
                {
                    // Make driver current and sync userdata
                    $this->current = $this->$child;
                    //$this->userdata =& $this->current->get_userdata();
                }
            }
            else
            {
                // Load new driver
                $this->load_driver($child);
            }
        }
    }
    
    // ------------------------------------------------------------------------
    
    public function get(array $terms = array())
    {
        // small fix, only ndw get requests can be empty...
        if (( ! isset($terms['q']) OR trim($terms['q']) == FALSE) && strpos(get_class($this->current), 'ndw') < 0)
        {
            show_error("How should I get data without a (q)uery term?");
            return;
        }
        
        return $this->current->get($terms);
    }
    
    // ------------------------------------------------------------------------
    
    public function http_code()
    {
        return $this->current->http_code();    
    }
    
    // ------------------------------------------------------------------------
    
    public function http_info()
    {
        return $this->current->http_info();
    }
}

// ------------------------------------------------------------------------

/**
 * Request_driver Class
 *
 * @package        CodeIgniter
 * @subpackage     Libraries
 * @category       Requests
 * @author         Ditmar Commandeur
 */
abstract class Request_driver extends CI_Driver {

    /**
     * CI Singleton
     *
     * @see    get_instance()
     * @var    object
     */
    protected $CI;
    protected $http_code;
    protected $http_info;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * Gets the CI singleton, so that individual drivers
     * don't have to do it separately.
     *
     * @return    void
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    // ------------------------------------------------------------------------

    /**
     * Decorate
     *
     * Decorates the child with the parent driver lib's methods and properties
     *
     * @param    object    Parent library object
     * @return    void
     */
    public function decorate($parent)
    {
        // Call base class decorate first
        parent::decorate($parent);

        // Call initialize method now that driver has access to $this->_parent
        $this->initialize();
    }

    // ------------------------------------------------------------------------

    /**
     * __call magic method
     *
     * Handles access to the parent driver library's methods
     *
     * @param    string    Library method name
     * @param    array    Method arguments (default: none)
     * @return    mixed
     */
    public function __call($method, $args = array())
    {
        // Make sure the parent library uses this driver
        $this->_parent->select_driver(get_class($this));
        return parent::__call($method, $args);
    }
    
    // ------------------------------------------------------------------------
    
    public function http_code()
    {
        return $this->http_code;    
    }
    
    // ------------------------------------------------------------------------
    
    public function http_info()
    {
        return $this->http_info;
    }

    // ------------------------------------------------------------------------

    /**
     * Initialize driver
     *
     * @return    void
     */
    protected function initialize()
    {
        // Overload this method to implement initialization
    }

    // ------------------------------------------------------------------------
    
    /**
    * Make a HTTP request based on cURL
    * 
    * @param mixed $url
    * @param array $headers
    * 
    * @return API results
    */
    protected function http($url, array $headers = array())
    {         
        $this->http_code = NULL;
        $this->http_info = NULL;
        
        $curl = curl_init();
 
        curl_setopt($curl, CURLOPT_USERAGENT, 'StatSocial Requester');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, array('Expect:')));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_URL, $url);
        
        $response = curl_exec($curl);
        
        $this->http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->http_info = curl_getinfo($curl);
        
        curl_close($curl);
        
        return $response;
    }
    
    // ------------------------------------------------------------------------
    
    protected function http_build_query(array $params, $implode = '&', $quotes = FALSE)
    {
        $values = array();
        
        foreach ($params as $key => $val)
        {                    
            $values[] = $key.'='.($quotes ? '"' : '').rawurlencode($val).($quotes ? '"' : '');
        }
        
        return implode($implode, $values);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Request method for a specific url
    * 
    * @param mixed $term
    */
    abstract public function get(array $terms);
}

/* End of file Request.php */
/* Location: ./system/libraries/Request/Request.php */