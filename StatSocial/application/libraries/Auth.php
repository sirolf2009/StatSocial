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
    }
    
    // -----------------------------------------------------------------------
    
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
            // check, did we exceed the max amount of login attempts or did this timeout already exceed?
            if ($user->attempts < 5 || time() > $user->last_attempt + $this->_timeout_time)
            {
                // last check if the password is the same as the encrypted one from the database.
                if (crypt($password, $user->password) === $user->password)            
                {
                    // update te user information for the session.
                    $this->update($user);
                    // update the user data in the database..
                    get_instance()->user_model->update($user->id, array('last_login' => time(), "attempts" => 0), TRUE);
                    // return TRUE since we logged in suscesfully.
                    return TRUE;
                }    
                      
                // check if the time exceeded the timeout? if so we reset this information.
                if (time() > $user->last_attempt + $this->_timeout_time)
                {
                    $attempts = 1;
                }
                else
                {
                    $attempts = $user->attempts + 1;    
                }
                
                // set this information, we use one method to avoid extra lines of code for reuse.
                get_instance()->user_model->update($user->id, array("last_attempt" => time(), "attempts" => $attempts), TRUE);
            }
            else
            {
                // return information about timeout, maximum amount of try's exceeded... wait X seconds.
            return 'Aantal inlog pogingen overschreden,<br> probeer het over <strong>'.(($user->last_attempt + $this->_timeout_time) - time()).' seconden</strong> opnieuw.';    
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
    * Method for updating user information in the session.
    * 
    * @access public
    * @param mixed $user
    * @return boolean
    */
    public function update($user = null)
    {
        if (empty($user))
        {
            $user = get_instance()->user_model->get($this->id());
        }
        
        if ( ! empty($user))
        {
            $data['id']         = $user->id;
            $data['name']       = $user->name;
            $data['last_login'] = $user->last_login;   
            
            get_instance()->session->set_userdata('auth', $data);
            
            return TRUE;
        }
        
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
        return crypt($password, '$2y$6$'.bin2hex(openssl_random_pseudo_bytes(22)));
    }
}