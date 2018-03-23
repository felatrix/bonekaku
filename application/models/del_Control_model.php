<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fin_model extends CI_Model {

    private $table = 'finishing_order';
    private $table2 = '';
    private $table3 = 'cfg_pekerja';

    function __construct()
    {
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

      public function SaveForm($form_data, $tbl = '') {
        $table = ($tbl == 'dist') ? $this->table2 : $this->table;        
        $this->db->insert($table, $form_data);

        if ($this->db->affected_rows() == '1') {
            if($tbl == 'dist'){ return TRUE; }
            else { return $this->db->insert_id(); }
        }

        return FALSE;
    }

    public function update_data($orderid, $form_data, $tbl = '') {
        $table = ($tbl == 'dist') ? $this->table2 : $this->table;        
        $this->db->where('id', $orderid);
        $status = $this->db->update($table,$form_data);
        return $status;
    }
                
    public function get_data_by_oid($oid, $slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tanggal';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select nama from ' . $this->table3 . ' where id = pekerjaid) as nama', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /*$search_arr = array(
                        'l.waktu' => $searchtext , 
                        'l.nomerhp' => $searchtext , 
                        'l.pesanout' => $searchtext ,  
                        'l.pesanin' => $searchtext );
        $this->db->or_like($search_arr);*/
        $query = $this->db->get_where($this->table, ['oid'=> $oid], $limit, $offset);
        return $query->result_array();
//        return $query->row_array();
//        $str = $this->db->last_query();
//        return $str;

    }

    public function sql_rm_data($orderid, $tbl = '') {
        $table = ($tbl == 'dist') ? $this->table2 : $this->table;        
        $result = $this->db->delete($table, array('id' => $orderid));
        return $result;
    }
    
    public function get_single_data($orderid) {
        $this->db->select('*,(select nama from ' . $this->table3 . ' where id = pekerjaid) as nama, (select jenisactivity from ' . $this->table3 . ' where id = pekerjaid) as jenisactivity', FALSE);
        $query = $this->db->get_where($this->table, array('id' => $orderid));
        return $query->row_array();
    }

    public function count_data_by_oid($oid) {
        $this->db->where(['oid'=> $oid]);
        $result = $this->db->count_all_results($this->table);
        if($result == 0){
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function sum_data_by_oid($oid, $field) {
        $this->db->select_sum($field);
        $query = $this->db->get_where($this->table, ['oid'=> $oid]);
        return $query->row_array();
    }

    public function pekerja_by_oid($oid) {
        $pekerja = "(SELECT GROUP_CONCAT(DISTINCT cp.nama ORDER BY cp.nama SEPARATOR ', ')) as pekerja";
        $this->db->select($pekerja, FALSE);
        $this->db->from($this->table3 . ' cp');
        $this->db->where('cp.id = fo.pekerjaid');
        $query = $this->db->get_where($this->table . ' fo', ['fo.oid'=> $oid]);
        return $query->row_array();
    }
    
}
?>