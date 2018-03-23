<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Activitylog_model extends CI_Model {

    private $table = 'act_log';

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
    public function SaveForm($form_data) {
        $this->db->insert($this->table, $form_data);

        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * function list_data()
     *
     * list data
     * @param $slug - text offset
     * @param $limit - int record limit
     * @param $searchtext - varchar keyword
     * @param $orderby - varchar field to order
     * @param $asc - varchar order direction asc or desc
     * @return array - database recordset
     */

    public function list_data($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'waktu';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $search_arr = ['waktu' => $searchtext ,
                        'account' => $searchtext ,
                        'ctrl' => $searchtext ,
                        'action' => $searchtext ,
                        'data' => $searchtext ];
        $this->db->or_like($search_arr);
        $query = $this->db->get_where($this->table, NULL, $limit, $offset);
        return $query->result_array();
    }

    public function getID($table){
        $this->db->select_max('id');
        $result = $this->db->get($table)->row_array();
        return $result['id'];
    }
    
}
?>