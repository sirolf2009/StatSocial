<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Auth
* 
* this class gives the option to make location authorised required.
* class has methods for logging in, logging out, updating and creating account.
* 
* @author Ditmar Commandeur
* @copyright 2014
* @category Library
* @version 1.0a
*/
class Auth {
    
    // rejection time in seconds after 5 failed login attempts
    private $_timeout_time = 30;
    // layer for crypting, see php.net for more info.
    private $_crypting_layer = '$2y$06$';
    
    /**
    * Constructor for class Auth
    * 
    * this start-up method will get all required information used for this class.
    * @return ::self 
    */
    public function __construct()
    {
        // load the session driver.
        get_instance()->load->driver('session');
        // load the user model, needed for any model adjusting
        get_instance()->load->model('user_model');
        // load the throttle model, needed for throttle methods.
        get_instance()->load->model('throttle_model');
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Method for loggin in a user
    * 
    * @param mixed $email
    * @param mixed $password
    * @access public
    * @return mixed (information about failed loggin)
    * @return boolean TRUE <- in case of correct login
    */
    public function login($email, $password)
    {
        // check if the submitted email is with any user..
       $user = get_instance()->user_model->get("email", $email);
       
        // if we have any user, let's continue
        if ( ! empty($user))
        {    
            // get throttle information
            $throttle = get_instance()->throttle_model->get(array("user" => $user->id, "ipaddress" => get_instance()->input->ip_address()));
            
            // check, did we exceed the max amount of login attempts or did this timeout already exceed?
            if (empty($throttle) || (int)$throttle->attempts < 5 || time() > (int)$throttle->last_attempt + $this->_timeout_time)
            {
                // last check if the password is the same as the encrypted one from the database.
                if (crypt($password, $this->_crypting_layer.$user->password) === $this->_crypting_layer.$user->password)            
                {
                    // update te user information for the session.
                    $this->update($user);
                    // update the user data in the database..
                    get_instance()->user_model->update($user->id, array('last_login' => time()), TRUE);
                    get_instance()->throttle_model->delete(array("user" => $user->id, "ipaddress" => get_instance()->input->ip_address()));
                    // return TRUE since we logged in suscesfully.
                    return TRUE;
                }    
                
                // check if we have any throttle information
                if ( ! empty($throttle))
                {      
                    // check if the time exceeded the timeout? if so we reset this information.
                    if (time() > (int)$throttle->last_attempt + $this->_timeout_time)
                    {
                        get_instance()->throttle_model->update(array("user" => $user->id, "ipaddress" => get_instance()->input->ip_address()), array("attempts" => 1, "last_attempt" => time())); 
                    }
                    else
                    {
                        get_instance()->throttle_model->update(array("user" => $user->id, "ipaddress" => get_instance()->input->ip_address()), array("attempts" => (int)$throttle->attempts + 1, "last_attempt" => time()));  
                    }
                }
                else
                {
                    get_instance()->throttle_model->insert(array("user" => $user->id, "ipaddress" => get_instance()->input->ip_address(), "attempts" => 1, "last_attempt" => time()));    
                }
            }
            else
            {
                // return information about timeout, maximum amount of try's exceeded... wait X seconds.
                return 'Aantal inlog pogingen overschreden,<br> probeer het over <strong>'.(((int)$throttle->last_attempt + $this->_timeout_time) - time()).' seconden</strong> opnieuw.';    
            }
        }
        
        // return false because there was not user found.
        return 'Ongeldige email/wachtwoord combinatie.';
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for logging out a user from the current session
    * 
    * @access public
    * @return null
    */
    public function logout()
    {
        get_instance()->session->unset_userdata('auth');
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for checking if the user is loggedin
    * 
    * @access public
    * @return boolean
    */
    public function is()
    {
        return is_array(get_instance()->session->userdata('auth'));
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for retrieving the id of the current session user
    * 
    * @access public
    * @returns mixed $session['id']
    */
    public function id()
    {
        $session = get_instance()->session->userdata('auth');
        return $session['id'];
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for retrieving the name of the current session user
    * 
    * @access public
    * @returns mixed $session['name']
    */
    public function name()
    {
        $session = get_instance()->session->userdata('auth');
        return $session['name'];
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for retrieving the last login moment.
    * 
    * @access public
    * @returns int $last_login
    */
    public function last_login()
    {
        $session = get_instance()->session->userdata('auth');
        return $session['last_login'];
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for updating user information in the session.
    * 
    * @access public
    * @param mixed $user
    * @return boolean
    */
    public function update($user = null)
    {
        // check if we received a user
        if (empty($user))             
        {
            // if not we will try to get it from the current logged in user..
            $user = get_instance()->user_model->get($this->id());
        }
        
        // if we did not receive a user or could not get it... skip this!
        if ( ! empty($user))
        {
            $data['id']         = $user->id;
            $data['name']       = $user->name;
            $data['last_login'] = $user->last_login;   
            
            get_instance()->session->set_userdata('auth', $data);
            
            return TRUE;
        }
        
        // just logout to make sure every "maybe" existing data is erased.
        $this->logout();
        return FALSE;
    }
    
    // -----------------------------------------------------------------------
    
    /**
    * Method for hashing a password submitted
    * 
    * @access public
    * @param mixed $password
    * @return encrypted $password
    */
    public function hash($password)
    {
        // we substract the crypting algoritme and the amount of rounds from the password...
        return substr(crypt($password, $this->_crypting_layer.bin2hex(openssl_random_pseudo_bytes(22))), strlen($this->_crypting_layer));
    }
}