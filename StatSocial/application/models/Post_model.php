<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Post_Model extends MY_Model {
    
    protected $table = 'posts';
    protected $primary_key = 'id';
    protected $fields = array('id', 'post_id', 'ndw_id', 'social_id', 'type', 'term', "message", "negative", "positive", "date", 'negative', 'positive');
    
    private $users = array();
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->driver('request'); 
    }
    
    // ------------------------------------------------------------------------
    
    public function get()
    {
        $args = func_get_args();
        $this->db->select("excludes.date AS exclude_date, social_users.name");
        $this->db->join("social_users", "social_users.social_id = posts.social_id AND social_users.type = posts.type");
        $this->db->join('excludes', 'excludes.social_id = social_users.social_id AND excludes.type = social_users.type', 'left');
        
        if (count($args) > 0)
        {
            return parent::get($args[0]);    
        } 
        return parent::get();   
    }  
    
    // ------------------------------------------------------------------------
    
    public function get_all()
    {
        $args = func_get_args();
        $this->db->select("excludes.date AS exclude_date, social_users.name");
        $this->db->join("social_users", "social_users.social_id = posts.social_id AND social_users.type = posts.type");
        $this->db->join('excludes', 'excludes.social_id = social_users.social_id AND excludes.type = social_users.type', 'left');
        
        if (count($args) > 0)
        {
            return parent::get_all($args[0]);    
        }
        return parent::get_all();
    }
    
    // ------------------------------------------------------------------------
    
    public function facebook($term, $ndw_id = -1)
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
        $since = $this->db->select_max('date')->where(array('type' => 'FACEBOOK', 'term' => $term))->get('posts')->row()->date;
        // get the persons to exclude...
        $excludes = $this->db->select('social_id')->where('type', 'FACEBOOK')->get('excludes')->result_array();
        // build the default query..
        $query = array('q' => 'file '.$term, 'type' => 'post', 'fields' => 'id,from.id,from.name,message,type,created_time', 'limit' => 100); // facebook will return only 10 records!
        // check if we have a since date, if so we need to set it.
        if ( ! is_null($since))
        {
            $query['since'] = $since;
        }
        
        // set the times and errors variable to zero.
        $times  = 0;
        $errors = 0;
        
        // while everything is going well and we are below the times amount and have less then 3 errors we can go
        while($times < 5 && $errors < 3)
        {         
            // store the facebook user ids so we can check if the user has a dutch locale..
            $user_ids = array();
            
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
                    if ($post->type !== 'status' OR ($post->type !== 'photo' AND empty($post->message)) OR substr($post->message, 0, 4) === 'http')
                    {
                        continue;
                    } 
                    
                    // check if post is not in exclusion list..
                    if (in_array($post->from->id, array_column($excludes, 'social_id')))
                    {
                        unset($data->data[$i]);
                        continue;    
                    }  
                    
                    $user_ids[] = $post->from->id;
                }
                
                // only do something if we have any good posts... else skip it.
                if (count($user_ids) > 0)
                {
                    $fql_ids = array_column(json_decode(json_encode($this->request->fql($user_ids)->data), TRUE), 'uid');
                    
                    foreach ($data->data  as $post)
                    {
                        if (in_array($post->from->id, $fql_ids))
                        {
                            $insert['post_id']      = substr($post->id, 16, 15);
                            $insert['ndw_id']       = $ndw_id;
                            $insert['social_id']    = $post->from->id;  
                            $insert['type']         = 'FACEBOOK';
                            $insert['term']         = $term;
                            $insert['message']      = $this->emjoi_remover($post->message);
                            $insert['date']         = strtotime($post->created_time);
                                  
                            // set the user in the global array for later..
                            $this->users['FACEBOOK'][$post->from->id] = $post->from->name; 
                            
                            // insert the obtained data...             
                            parent::insert($insert, TRUE);
                        }
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
        
        // save the users..
        $this->users();
        
        // set the time limit to 30 seconds..
        set_time_limit(30);
        
        // if we are done, we should kill the page!
        return;     
    }
    
    // ------------------------------------------------------------------------
    
    public function twitter($term, $ndw_id = -1)
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
        $since_id = $this->db->select_max('post_id')->where(array('type' => 'TWITTER', 'term' => $term))->get('posts')->row()->post_id;
        // get the persons to exclude...
        $excludes = $this->db->select('social_id')->where('type', 'TWITTER')->get('excludes')->result_array();
        // build the default query..
        $query = array('q' => 'file '.$term, 'result_type' => 'recent', 'lang' => 'nl', 'count' => 100);
        // check if we have a since_id, if so we need to set it.
        if ( ! is_null($since_id))
        {
            $query['since_id'] = $since_id;
        }
        
        // set the times and errors variable to zero.
        $times  = 0;
        $errors = 0;
        
        // while everything is going well and we are below the times amount and have less then 3 errors we can go
        while($times < 5 && $errors < 3)
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
                    // skip any retweets..
                    if (substr($post->text, 0, 4) === 'RT @' OR 
                        stripos($post->user->name, 'nws') !== FALSE OR 
                        stripos($post->user->name, 'nieuws') !== FALSE OR 
                        stripos($post->user->name, '112') !== FALSE OR 
                        stripos($post->user->name, 'news') !== FALSE OR
                        stripos($post->user->name, 'algemeen') !== FALSE OR
                        stripos($post->user->name, 'citytweet') !== FALSE OR
                        stripos($post->user->name, 'dichtbij') !== FALSE OR
                        stripos($post->user->name, '.nl') !== FALSE OR
                        stripos($post->user->name, $term) !== FALSE)
                    {
                        continue;
                    }
                    
                    // check if post is not in exclusion list..
                    if ( ! in_array($post->user->id_str, array_column($excludes, 'social_id')))
                    {
                        $insert['post_id']      = $post->id_str;
                        $insert['ndw_id']       = $ndw_id;
                        $insert['social_id']    = $post->user->id_str;  
                        $insert['type']         = 'TWITTER';
                        $insert['term']         = $term;
                        $insert['message']      = $this->emjoi_remover($post->text);
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
                
                // let's get the next results from the search_metadata given by Twitter and put it in the query to rebuild.
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
        
        // save the users..
        $this->users();
        
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
                $existing = $this->db->select('social_id')->where('type', $media)->get('social_users')->result_array();
                
                // if user data exists
                if ( ! isset($this->users[$media]))
                {
                    continue;
                }
                
                // loop through each user.
                foreach ($this->users[$media] as $id => $user)
                {
                    // check if the user does not already exists, if so we remove it and skip this user..
                    if (in_array($id, array_column($existing, 'social_id')))
                    {
                        unset($this->users[$media][$id]);
                        continue;
                    }
                    
                    // user does not yet exists.. insert it.
                    $insert[] = array('social_id' => $id, 'type' => $media, 'name' => $user);
                }
            }

            if ( ! empty($insert))
            {
                // insert the users.
                $this->db->insert_batch('social_users', $insert);    
            }

            // clear the users.. since we already inserted them.
            $this->users = array();
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

    // ------------------------------------------------------------------------
    /*
     * search for a post
     * @param $platform - may be either Facebook, Twitter or Allebij
     * @param $user - the user to search for
     * @param $regex - the message to search for
     * @param $page - not used
     * @return an array with the query result
    */
    public function search($platform, $user, $regex, $page) {
        //construct the query
        $query = ("SELECT posts.* , social_users.name ".
            "FROM posts ".
            "LEFT JOIN social_users ON posts.social_id = social_users.social_id ".
            "WHERE posts.message LIKE  '%".$regex."%' ".
            //if platform is set, filter for the specified platform
            ($platform=="Allebij" ? "" : "AND social_users.type = '".strtoupper($platform)."' ").
            //if user is set, filter for the specified user
            ($user=="" ? "" : "AND social_users.name = ".$user." ")
            //"LIMIT=".($page*30).",".(30+$page*30)
            );
        //send the query to the database
        $data = $this->db->query($query);
        //return all our rows
        return $data->result();
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Removes unwanted emjoi icons/emoticons from the text..
    * 
    * @param mixed $text     \
    * @returns clean text
    */
    private function emjoi_remover($text)
    {
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    }
}