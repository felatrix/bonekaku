<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unread_model extends CI_Model {

    private $table = 'act_unread';

    function __construct() {
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * function SaveForm()
     *
     * insert form data
     * @param $form_data - array
     * @return Bool - TRUE or FALSE
     */
//    public function SaveForm($form_data) {
//        $this->db->insert($this->table, $form_data);
//
//        if ($this->db->affected_rows() == '1') {
//            return $this->db->insert_id();
//        }
//
//        return FALSE;
//    }

    public function get_single_data($oid, $coid = NULL) {
        $where = ['oid' => $oid];
        if(!empty($coid)){
            $where['coid'] = $coid;
        }
        $query = $this->db->get_where($this->table, $where);
        return $query->row_array();
    }

    public function getID($table){
        $this->db->select_max('id');
        $result = $this->db->get($table)->row_array();
        return $result['id'];
    }

    public function set_read($ctlr, $oid, $coid = NULL) {
        if($ctlr == 'main' && !empty($oid)){
            $cek = $this->get_single_data($oid);
            if(empty($cek)){
                $this->db->insert($this->table, ['oid' => $oid, $ctlr => 1]);
            } else {
                $this->db->where('oid', $oid);
                $this->db->update($this->table,[$ctlr => 1]);
            }
        } elseif(!empty($ctlr) && !empty($coid) && !empty($oid)){
            $cek = $this->get_single_data($oid, $coid);
            if(empty($cek)){
                $this->db->insert($this->table, ['oid' => $oid, 'coid' => $coid, $ctlr => 1, 'main' => 1]);
            } else {
                $this->db->where(['oid' => $oid, 'coid' => $coid]);
                $this->db->update($this->table,[$ctlr => 1, 'main' => 1]);
            } 
        }

        if ($this->db->affected_rows() >= '1') {
            return TRUE;
        }
        return FALSE;
    }
    
}
?>