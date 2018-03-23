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

class Unread {

    var $CI;

    /**
     * Constructor
     */
    function __construct() {
        // Obtain a reference to the ci super object
        $this->CI = & get_instance();
    }

//    function save_log($account, $ctlr, $action, $data = '') {
//        $form_data = [
//            'account' => $account->username,
//            'waktu' => date('Y-m-d H:i:s'),
//            'ctrl' => $ctlr,
//            'action' => $action,
//            'data' => json_encode($data)
//        ];
//        
//        $this->CI->load->model('lib/unread_model');
//        return $this->CI->unread_model->SaveForm($form_data);
//    }

    function cek_unread($ctlr, $oid, $coid = NULL) {
        $this->CI->load->model('lib/unread_model');
        if(!empty($coid)){
            $result = $this->CI->unread_model->get_single_data($oid, $coid);            
        } else {
            $result = $this->CI->unread_model->get_single_data($oid);            
        }
        if(!empty($result)){
            if($result[$ctlr] == FALSE){
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    function get_id($table){
        $this->CI->load->model('lib/unread_model');
        return $this->CI->unread_model->getID($table);
    }

    function set_read($ctlr, $oid, $coid = NULL){        
        $this->CI->load->model('lib/unread_model');
        if(!empty($coid)){
            return $this->CI->unread_model->set_read($ctlr, $oid, $coid);
        } else {
            return $this->CI->unread_model->set_read($ctlr, $oid);
        }
    }
    
}

/* End of file Activitylog.php */
/* Location: ./application/libraries/Activitylog.php */
