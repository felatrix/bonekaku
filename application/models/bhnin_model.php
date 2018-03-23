<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bhnin_model extends CI_Model {
    private $table = 'cfg_stok_bahan';
    private $table2 = 'cfg_boneka_bahan';
    private $table3 = 'cfg_boneka_warna';

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

    public function update_data($dataid, $form_data) {
        $this->db->where('id', $dataid);
        $status = $this->db->update($this->table,$form_data);        
        return $status;
    }
        
        
    public function list_data($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tgl';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select warna from '.$this->table3.' where id = warnaid) as color', FALSE);
        $this->db->select('(select bahan from '.$this->table2.' where id = bahanid) as bahan', FALSE);
        $query = $this->db->get_where($this->table, NULL, $limit, $offset);
        return $query->result_array();
    }

    public function sql_rm_data($dataid) {
        $result = $this->db->delete($this->table, array('id' => $dataid));
        return $result;
    }
    
    public function get_single_data($dataid) {
        $this->db->select('*,(select warna from '.$this->table3.' where id = warnaid) as color', FALSE);
        $this->db->select('(select bahan from '.$this->table2.' where id = bahanid) as bahan', FALSE);
        $query = $this->db->get_where($this->table, array('id' => $dataid));
        return $query->row_array();
    }
        
}
?>