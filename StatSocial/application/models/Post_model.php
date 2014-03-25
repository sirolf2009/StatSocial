<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Post_Model extends MY_Model {
    
    protected $table = 'posts';
    protected $primary_key = 'id';
    protected $fields = array('id', 'post_id', 'ndw_id', 'social_id', 'type', "message", "date");
    
    private $users = array();
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->driver('request'); 
    }
    
    // ------------------------------------------------------------------------
    
    public function facebook($term)
    {
        // if the term is empty, stop doing anything!
        if (trim($term)==FALSE)
        {
            return;    
        }
        
        // set the time limit to inifinite..
        set_time_limit(0);
        
        // select the twitter driver..
        $this->request->select_driver('facebook');
        // get the last time, so we will only get post higher then these..
        $since = $this->db->select_max('date')->where('type', 'FACEBOOK')->get('posts')->row()->date;
        // get the persons to exclude...
        $excludes = $this->db->select('social_id')->where('type', 'FACEBOOK')->get('excludes')->result_array();
        // build the default query..
        $query = array('q' => $term, 'type' => 'post', 'fields' => 'id,from.id,from.name,message,type,created_time'); // facebook will return only 10 records!
        // check if we have a since date, if so we need to set it.
        if ( ! is_null($since))
        {
            $query['since'] = $since;
        }
        
        // set the times and errors variable to zero.
        $times  = 0;
        $errors = 0;
        
        // while everything is going well and we are below the times amount and have less then 3 errors we can go
        while($times < 10 && $errors < 3)
        {         
            // get the data...
            $data = $this->request->get($query); 
            
            // if the http_code from our request is not 200, we should try again..
            if ($this->request->http_code() === 200)
            {
                // we the status is empty, it means we are finished with getting tweets.
                if (count($data->data) == 0)
                {
                    break;
                }
                
                // loop through each result.
                foreach ($data->data as $post)
                {                
                    // we should skip everything that is not a status!
                    if ($post->type !== 'status')
                    {
                        continue;
                    } 
                    
                    // check if post is not in exclusion list..
                    if ( ! in_array($post->from->id, array_column($excludes, 'social_id')))
                    {
                        $insert['post_id']      = substr($post->id, 16, 15);
                        $insert['ndw_id']       = -1;
                        $insert['social_id']    = $post->from->id;  
                        $insert['type']         = 'FACEBOOK';
                        $insert['message']      = $post->message;
                        $insert['date']         = strtotime($post->created_time);
                              
                        // set the user in the global array for later..
                        $this->users['FACEBOOK'][$post->from->id] = $post->from->name; 
                              
                        // insert the obtained data...             
                        parent::insert($insert, TRUE);
                    }  
                }
                
                // let's get the next results from the saerch_metadata given by Twitter and put it in the query to rebuild.
                $query['until'] = substr($data->paging->next, strlen($data->paging->next) - 10, 10);
            }
            else
            {
                // we got an error, so we should update this one
                $errors++;
            }
            
            // update the amount of times
            $times++;
        }
        
        // set the time limit to 30 seconds..
        set_time_limit(30);
        
        // if we are done, we should kill the page!
        return;     
    }
    
    // ------------------------------------------------------------------------
    
    public function twitter($term)
    {
        // if the term is empty, stop doing anything!
        if (trim($term)==FALSE)
        {
            return;    
        }
        
        // set the time limit to inifinite..
        set_time_limit(0);
        
        // select the twitter driver..
        $this->request->select_driver('twitter');
        // get the last id, so we will only get post higher then these..
        $since_id = $this->db->select_max('post_id')->where('type', 'TWITTER')->get('posts')->row()->post_id;
        // get the persons to exclude...
        $excludes = $this->db->select('social_id')->where('type', 'TWITTER')->get('excludes')->result_array();
        // build the default query..
        $query = array('q' => $term, 'result_type' => 'recent', 'lang' => 'nl', 'count' => 100);
        // check if we have a since_id, if so we need to set it.
        if ( ! is_null($since_id))
        {
            $query['since_id'] = $since_id;
        }
        
        // set the times and errors variable to zero.
        $times  = 0;
        $errors = 0;
        
        // while everything is going well and we are below the times amount and have less then 3 errors we can go
        while($times < 10 && $errors < 3)
        {            
            // get the data...
            $data = $this->request->get($query);  
            
            // if the http_code from our request is not 200, we should try again..
            if ($this->request->http_code() === 200)
            {
                // we the status is empty, it means we are finished with getting tweets.
                if (count($data->statuses) == 0)
                {
                    break;
                }
                
                // loop through each result.
                foreach ($data->statuses as $post)
                {
                    // check if post is not in exclusion list..
                    if ( ! in_array($post->user->id_str, array_column($excludes, 'social_id')))
                    {
                        $insert['post_id']      = $post->id_str;
                        $insert['ndw_id']       = -1;
                        $insert['social_id']    = $post->user->id_str;  
                        $insert['type']         = 'TWITTER';
                        $insert['message']      = $post->text;
                        $insert['date']         = strtotime($post->created_at);
                              
                        // set the user in the global array for later..
                        $this->users['TWITTER'][(int)$post->user->id_str] = $post->user->name; 
                              
                        // insert the optained data...             
                        parent::insert($insert, TRUE);
                    }  
                }

                // check if there are any results left.. if not we should stop!
                if ( ! isset($data->search_metadata->next_results))
                {
                    break;
                }
                
                // let's get the next results from the saerch_metadata given by Twitter and put it in the query to rebuild.
                parse_str(str_replace('?', '', $data->search_metadata->next_results), $query);
            }
            else
            {
                // we got an error, so we should update this one
                $errors++;
            }
            
            // update the amount of times
            $times++;
        }
        
        // set the time limit to 30 seconds..
        set_time_limit(30);
        
        // if we are done, we should return
        return;
    }
    
    // ------------------------------------------------------------------------
    
    public function users()
    {
        if ( ! empty($this->users))
        {
            $insert = array();
            
            // loop through the social media..
            foreach (array('TWITTER', 'FACEBOOK') as $media)    
            {
                // get the persons to exclude...
                $excisting = $this->db->select('social_id')->where('type', $media)->get('social_users')->result_array();
                
                // if user data exists
                if ( ! isset($this->users[$media]))
                {
                    continue;
                }
                
                // loop through each user.
                foreach ($this->users[$media] as $id => $user)
                {
                    // check if the user does not already exists, if so we remove it and skip this user..
                    if (in_array($user, array_column($excisting, 'social_id')))
                    {
                        unset($this->users[$media][$id]);
                        continue;
                    }
                    
                    // user does not yet exists.. insert it.
                    $insert[] = array('social_id' => $id, 'type' => $media, 'name' => $user);
                }
            }
            
            $this->db->insert_batch('social_users', $insert);
        }
    }
    
    // ------------------------------------------------------------------------
    
    public function exclude($id, $type)
    {
        $type = strtoupper($type);
        
        if (in_array($type, array('TWITTER', 'FACEBOOK')))
        {
            $insert = $this->db->insert_string("excludes", array('social_id' => $id, 'type' => $type, 'date' => time()));
            
            $this->db->query(str_replace("INSERT INTO", "INSERT IGNORE INTO", $insert)); 
            
            return TRUE;   
        }
        
        return FALSE;
    }
}