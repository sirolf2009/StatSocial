<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Statistic_Model extends CI_Model {
  
    public function get_table()
    {
        $this->db->select('locations.roadnumber, SUM(posts.negative) AS sentiment, COUNT(posts.id) AS amount, COUNT(ndw.id) AS events');
        $this->db->from('posts');
        $this->db->join('ndw', 'ndw.id = posts.ndw_id');
        $this->db->join('locations', 'locations.id = ndw.location');
        $this->db->group_by('locations.roadnumber');
        $this->db->having('amount >= 5');
        
        $results = $this->db->get()->result();
        
        foreach ($results AS $i => &$result)
        {
            $result->finalscore = round($result->sentiment/$result->amount/$result->events, 3); 
        }

        usort($results, function($a, $b) 
        {
            return $a->finalscore < $b->finalscore;   
        });
        
        return $results;
    }   
    
    public function get_highest()
    {
        return current($this->get_table());
    }  
    
    public function get_dashboard()
    {
        $dashboard = array();
        
        foreach ($this->db->select('COUNT(social_id) AS amount, type')->group_by('type')->get('social_users')->result() AS $result)
        {
            $dashboard['social_users']['amount']      = isset($dashboard['social_users']['amount']) ? $dashboard['social_users']['amount'] + $result->amount : $result->amount;
            $dashboard['social_users'][$result->type] = intval($result->amount);
        }
        
        foreach ($this->db->select('COUNT(id) AS amount, type')->group_by('type')->get('posts')->result() AS $result)
        {
            $dashboard['posts']['amount']      = isset($dashboard['posts']['amount']) ? $dashboard['posts']['amount'] + $result->amount : $result->amount;
            $dashboard['posts'][$result->type] = intval($result->amount);
        }
        
        foreach ($this->db->select('COUNT(id) AS amount, type')->group_by('type')->get('ndw')->result() AS $result)
        {
            $dashboard['ndw']['amount']      = isset($dashboard['ndw']['amount']) ? $dashboard['ndw']['amount'] + $result->amount : $result->amount;
            $dashboard['ndw'][$result->type] = intval($result->amount);
        }
        
        $start = strtotime('-6 hours');
                             
        while ($start <= time() + 900)
        {          
            if (date('i', $start) < 15)
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $start).", 00)"] = 0;  
            }
            else if (date('i', $start) >= 15 && date('i', $start) < 30)
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $start).", 15)"] = 0;      
            }
            else if (date('i', $start) >= 30 && date('i', $start) < 45)
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $start).", 30)"] = 0;      
            }
            else
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $start).", 45)"] = 0;    
            }

            $start += 900;
        }
        
        foreach ($this->db->select('COUNT(id) AS amount, date')->where('date >=', strtotime('-6 hours'))->group_by('date')->get('logs')->result() AS $result)
        {
            if (date('i', $result->date) < 15)
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $result->date).", 00)"] += intval($result->amount);  
            }
            else if (date('i', $result->date) >= 15 && date('i', $result->date) < 30)
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $result->date).", 15)"] += intval($result->amount);      
            }
            else if (date('i', $result->date) >= 30 && date('i', $result->date) < 45)
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $result->date).", 30)"] += intval($result->amount);      
            }
            else
            {
                $dashboard['logs']["Date.UTC(".date('Y, m-1, d, H-1', $result->date).", 45)"] += intval($result->amount);    
            }
        }
        
        return $dashboard;
    }   
}