<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * StatSocial Helpers
 *
 * @package        CodeIgniter
 * @subpackage     Helpers
 * @category       Helpers
 * @author         Ditmar Commandeur
 */

// ------------------------------------------------------------------------

if ( ! function_exists('is_active'))
{
    function is_active($value, $length = 'auto', $classes = array())
    {
        $uri = get_instance()->uri->segment_array();
        
        if ($length !== 'auto')
        {
            while(count($uri) > (int)$length)
            {
                unset($uri[count($uri)]);
            }
        }

        $uri = implode('/', $uri);

        if ( ! is_array($classes))
        {
            $classes = array($classes);
        }
        
        if (count($classes) > 0)
        {
            if ($uri === $value)
            {
                $classes[] = 'active';
            }
            
            return ' class="'.implode(' ', $classes).'"';    
        }
        
        return ($uri === $value ? ' class="active"' : '');
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('alerts'))
{
    function alerts($group = 'all', $keep = FALSE)
    {
        $output = '';
        
        foreach (array('success', 'info', 'danger', 'warning') as $type)
        {
            $messages = get_instance()->session->flashdata("alerts_{$type}");
            
            if ( ! empty($messages))
            {
                $alerts[$type] = $messages;    
            } 
        }
        
        if ($keep)
        {
            get_instance()->session->keep_flashdata('alerts');
        }
        
        if ( ! empty($alerts))
        {
            foreach ($alerts as $type => $messages)
            {
                if ($group !== 'all' && $type !== $group)
                {
                    continue;    
                }    
                
                foreach ($messages as $message)
                {
                    $output .= alert($type, $message);    
                }
            }
        }
        
        return $output;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('create_alert'))
{
    function create_alert($type, $message)
    {
        if (in_array($type, array('success', 'info', 'danger', 'warning')))
        {
            $alerts = get_instance()->session->flashdata("alerts_{$type}");
        
            if (empty($alerts))
            {
                $alerts = array();
            }
            
            $alerts[] = $message;
            
            get_instance()->session->set_flashdata("alerts_{$type}", $alerts);
        }
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('alert'))
{
    function alert($type, $message, $dismiss = TRUE)
    {
        return '<p class="alert alert-'.$type.'">'.($dismiss ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '').$message.'</p>';   
    }
}

/* End of file statsocial_helper.php */
/* Location: ./application/helpers/statsocial_helper.php */