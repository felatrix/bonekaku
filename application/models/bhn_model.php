<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bhn_model extends CI_Model {

    private $table = 'cutting_bahan';
    private $table2 = 'cfg_boneka_bahan';
    private $table3 = 'cfg_boneka_warna';

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

      public function SaveForm($form_data)
      {
              $this->db->insert($this->table, $form_data);

              if ($this->db->affected_rows() == '1')
              {
                      return TRUE;
              }

              return FALSE;
      }
        
    public function update_data($orderid, $form_data) {
        $this->db->where('id', $orderid);
        $status = $this->db->update($this->table,$form_data);        
        return $status;
    }
        
        
    public function get_data_by_oid($oid, $slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'asc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'bahan';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select warna from '.$this->table3.' where id = cb.warna) as color', FALSE);
        $this->db->select('(select bahan from '.$this->table2.' where id = jenis) as bahan', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /*$search_arr = array(
                        'l.waktu' => $searchtext , 
                        'l.nomerhp' => $searchtext , 
                        'l.pesanout' => $searchtext ,  
                        'l.pesanin' => $searchtext );
        $this->db->or_like($search_arr);*/
        $query = $this->db->get_where($this->table . ' cb', ['oid'=> $oid], $limit, $offset);
        return $query->result_array();
//        $str = $this->db->last_query();
//        return $str;

    }

    public function sql_rm_data($orderid) {
        $result = $this->db->delete($this->table, array('id' => $orderid));
        return $result;
    }
    
    public function get_single_data($orderid) {
        $this->db->select('*,(select warna from '.$this->table3.' where id = cb.warna) as color', FALSE);
        $this->db->select('(select bahan from '.$this->table2.' where id = jenis) as bahan', FALSE);
        $query = $this->db->get_where($this->table . ' cb', array('id' => $orderid));
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

}
?>