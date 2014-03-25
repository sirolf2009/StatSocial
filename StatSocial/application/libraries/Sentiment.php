<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Sentiment
* 
* This class will try to anlyse the sentiment value of dutch words/text.
* 
* @author Ditmar Commandeur
* @copyright 2014
* @category Library
* @version 0.1a
*/
class Sentiment {
    
    private $db;
    
    public function __construct()
    {
        // load the database into variable, we will need it later.
        $this->db =& get_instance()->db;
    }
    
    // -----------------------------------------------------------------------

    /**
    * Analyse a sentence, will search for sentimental values inside the database 
    * and compare them using regex against your sentence.
    * 
    * @param mixed $sentence
    * @param mixed $type (text/array(positive, negative))
    */
    public function analyse($sentence, $array = FALSE)
    {
        // reset the collection values for positive and negative
        $positive = 0;
        $negative = 0;

        $sql = "SELECT type, regex, false_positive, false_negative FROM `sentiments` WHERE MATCH (`regex`) AGAINST('".$this->db->escape_like_str($sentence)."') > 1.54321";
        
        foreach($this->db->query($sql)->result() AS $result)
        {                                             
            if (@preg_match("/.*{$result->regex}[ \\.!\\?$].*/i", $sentence))
            {
                if ($result->type === 'POSITIVE')
                {
                    $positive += (1 - $result->false_positive);
                }
                else
                {
                    $negative += $result->false_negative;
                }
            }         
        }      
        
        if ($array)
        {
            return array('negative' => $negative, 'positive' => $positive);    
        }
        
        return ($negative > $positive) ? 'NEGATIVE' : 'POSITIVE';        
    }
}