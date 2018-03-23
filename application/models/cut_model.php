<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cut_model extends CI_Model {

    private $table = 'cutting_order';
    private $table2 = 'cfg_pekerja';
    private $table3 = 'pretel_order';
    private $table4 = 'main_order';
    private $table7 = 'cfg_boneka_performance';

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
        
        
    public function get_data_by_oid($oid, $slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'id';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = bywho) as nama', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /*$search_arr = array(
                        'l.waktu' => $searchtext , 
                        'l.nomerhp' => $searchtext , 
                        'l.pesanout' => $searchtext ,  
                        'l.pesanin' => $searchtext );
        $this->db->or_like($search_arr);*/
        $query = $this->db->get_where($this->table, ['oid'=> $oid], $limit, $offset);
        return $query->result_array();
//        $str = $this->db->last_query();
//        return $str;

    }

    public function sql_rm_data($orderid) {
        $result = $this->db->delete($this->table, array('id' => $orderid));
        return $result;
    }
    
    public function get_single_data($orderid) {
        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = bywho) as nama, (select jenisactivity from ' . $this->table2 . ' where id = bywho) as jenisactivity', FALSE);
        $this->db->select('(select cbf.itemjahitan from ' . $this->table7 . ' cbf, ' . $this->table4 . ' mo where cbf.id = mo.itemid AND mo.id = oid) as itemjahitan', FALSE);
        $this->db->select('(select warnaboneka from ' . $this->table4 . ' where id = oid) as warnaboneka', FALSE);
        $this->db->select('(select bahanboneka from ' . $this->table4 . ' where id = oid) as bahanboneka', FALSE);
        $query = $this->db->get_where($this->table, array('id' => $orderid));
        return $query->row_array();
    }
    
    public function sum_data_by_oid($oid, $field) {
        $this->db->select_sum($field);
        $query = $this->db->get_where($this->table, ['oid'=> $oid]);
        return $query->row_array();
    }

    public function poco_by_oid($oid, $slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'co.date';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*');
        $this->db->select('(select status from ' . $this->table3 . ' where oid = '.$oid.' AND coid = co.id) as status', FALSE);
        $this->db->select('(select selesai from ' . $this->table3 . ' where oid = '.$oid.' AND coid = co.id) as selesai', FALSE);
        $this->db->select('(select id from ' . $this->table3 . ' where oid = '.$oid.' AND coid = co.id) as poid', FALSE);
        $this->db->select('(select p.nama from ' . $this->table3 . ' po,' . $this->table2 . ' p where p.id = po.pekerjaid AND po.oid = '.$oid.' AND po.coid = co.id) as nama', FALSE);
        $this->db->where('co.date NOT LIKE \'0000-00-00\' ');       
//        $this->db->from($this->table3 . ' po');
        $query = $this->db->get_where($this->table .' co', ['co.oid'=> $oid], $limit, $offset);
        return $query->result_array();
    }
    
    public function task_by_oid($oid) {
        $this->db->group_by('id');            
        $this->db->select('id');
        $query = $this->db->get_where($this->table, ['oid'=> $oid]);
        return $query->result_array();
    }
    
    public function count_finish_percoid($oid) {
        $this->db->select('id');
        $this->db->where('date NOT LIKE \'0000-00-00\'');
        $query = $this->db->get_where($this->table, ['oid'=> $oid]);
        return $query->result_array();
    }        
    
}
?>