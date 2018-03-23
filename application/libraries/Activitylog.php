<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed'); // Remove line to use class outside of codeigniter
    /*
     * Logging library for use with codeigniter
     * 
     * @author arieedzig <arieedzig@gmail.com>
     * @link https://github.com/arieedzig
     * @package Codeigniter
     * @subpackage logging
     *
     */

class Activitylog {

    var $CI;

    /**
     * Constructor
     */
    function __construct() {
        // Obtain a reference to the ci super object
        $this->CI = & get_instance();
    }

    function save_log($account, $ctlr, $action, $data = '') {
        $form_data = [
            'account' => $account->username,
            'waktu' => date('Y-m-d H:i:s'),
            'ctrl' => $ctlr,
            'action' => $action,
            'data' => json_encode($data)
        ];
        
        $this->CI->load->model('lib/activitylog_model');
        return $this->CI->activitylog_model->SaveForm($form_data);
    }

    function show_log($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $this->CI->load->model('lib/activitylog_model');
        return $this->CI->activitylog_model->list_data($slug, $limit, $searchtext, $orderby, $asc);
    }
    
    function get_id($table){
        $this->CI->load->model('lib/activitylog_model');
        return $this->CI->activitylog_model->getID($table);   
    }
    
}

/* End of file Activitylog.php */
/* Location: ./application/libraries/Activitylog.php */
