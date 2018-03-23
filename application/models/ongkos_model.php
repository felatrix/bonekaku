<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ongkos_model extends CI_Model {
    private $table = 'cfg_ongkos';
    private $table2 = 'cfg_boneka_bahan';
    private $table3 = 'cfg_boneka_performance';

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
        
    public function update_data($dataid, $form_data) {
        $this->db->where('id', $dataid);
        $status = $this->db->update($this->table,$form_data);        
        return $status;
    }
        
        
    public function list_data($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'itemjahitan';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select itemjahitan from ' . $this->table3 . ' where id = jahitanid) as itemjahitan', FALSE);
        $this->db->select('(select bahan from '.$this->table2.' where id = bahanid) as bahan', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /*$search_arr = array(
                        'l.waktu' => $searchtext , 
                        'l.nomerhp' => $searchtext , 
                        'l.pesanout' => $searchtext ,  
                        'l.pesanin' => $searchtext );
        $this->db->or_like($search_arr);*/
        $query = $this->db->get_where($this->table, NULL, $limit, $offset);
        return $query->result_array();
    }

    public function sql_rm_data($dataid) {
        $result = $this->db->delete($this->table, array('id' => $dataid));
        return $result;
    }
    
    public function get_single_data($dataid) {
        $this->db->select('*,(select itemjahitan from ' . $this->table3 . ' where id = jahitanid) as itemjahitan', FALSE);
        $query = $this->db->get_where($this->table, array('id' => $dataid));
        return $query->row_array();
    }
    
    public function json_data() {
        $order = 'itemjahitan';
        $this->db->order_by($order, 'asc');
        $this->db->select('id, itemjahitan, concat(itemjahitan,"-",minperform) as name', FALSE);
        $query = $this->db->get_where($this->table, NULL, 10);
        return $query->result_array();
    }
    
}
?>