<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sew_model extends CI_Model {

    private $table = 'sewing_order';
    private $table2 = 'sewing_dist';
    private $table3 = 'cfg_pekerja';
    private $table4 = 'pretel_order';
    private $table5 = 'cutting_order';
    private $table6 = 'main_order';
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
                
    public function get_data_by_oid($oid, $slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $tbl = '', $coid = '') {
        $offset = $slug;
        $table = ($tbl == 'dist') ? $this->table2 : $this->table;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'nama';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        if($tbl == 'dist'){
            $this->db->select('*,(select nama from ' . $this->table3 . ' where id = pekerjaid) as nama', FALSE);
        }
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /*$search_arr = array(
                        'l.waktu' => $searchtext , 
                        'l.nomerhp' => $searchtext , 
                        'l.pesanout' => $searchtext ,  
                        'l.pesanin' => $searchtext );
        $this->db->or_like($search_arr);*/
        $query = $this->db->get_where($table, ['oid'=> $oid, 'coid'=> $coid], $limit, $offset);
        return $query->result_array();
    }

    public function sql_rm_data($orderid, $tbl = '') {
        $table = ($tbl == 'dist') ? $this->table2 : $this->table;        
        $result = $this->db->delete($table, array('id' => $orderid));
        return $result;
    }
    
    public function get_single_data($orderid) {
        $this->db->select('*,(select nama from ' . $this->table3 . ' where id = pekerjaid) as nama, (select jenisactivity from ' . $this->table3 . ' where id = pekerjaid) as jenisactivity', FALSE);
        $query = $this->db->get_where($this->table2, array('id' => $orderid));
        return $query->row_array();
    }

    public function count_data_by_oid($oid, $coid = '') {
        $where = ['oid'=> $oid];
        if(!empty($coid)){
            $where['coid'] = $coid;
        }
        $this->db->where($where);
        $result = $this->db->count_all_results($this->table2);
        if($result == 0){
            return FALSE;
        } else {
//            return TRUE;
            return $result;
        }
    }

    public function sum_data_by_oid($oid, $coid, $field) {
        $this->db->select_sum($field);
        $this->db->where('(finishdate != \'0000-00-00\' OR finishdate != NULL)');
        $query = $this->db->get_where($this->table2, ['oid'=> $oid, 'coid'=> $coid]);
        return $query->row_array();
    }    

    public function pekerja_by_oid($oid, $coid) {
        $pekerja = "(SELECT GROUP_CONCAT(DISTINCT cp.nama ORDER BY cp.nama SEPARATOR ', ')) as pekerja";
        $this->db->select($pekerja, FALSE);
        $this->db->from($this->table3 . ' cp');
        $this->db->where('cp.id = fo.pekerjaid');
        $query = $this->db->get_where($this->table2 . ' fo', ['fo.oid'=> $oid, 'fo.coid'=> $coid]);
        return $query->row_array();
    }

    public function soco_by_oid($oid, $slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'jumlah';
        } else {
            $order = $orderby;            
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,mo.itemid');
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from ' 
            . $this->table5 . ' where oid = po.oid AND id = po.coid order by date desc limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from ' 
            . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate',FALSE);
        $this->db->select('(select jml from ' . $this->table5 . ' where oid = po.oid AND id = po.coid) as jumlah', FALSE);
//        $this->db->select('(select status from ' . $this->table3 . ' where oid = '.$oid.' AND coid = co.id) as status', FALSE);
//        $this->db->select('(select selesai from ' . $this->table3 . ' where oid = '.$oid.' AND coid = co.id) as selesai', FALSE);
//        $this->db->select('(select id from ' . $this->table3 . ' where oid = '.$oid.' AND coid = co.id) as poid', FALSE);
//        $this->db->select('(select p.nama from ' . $this->table3 . ' po,' . $this->table2 . ' p where p.id = po.pekerjaid AND po.oid = '.$oid.' AND po.coid = co.id) as nama', FALSE);
        $this->db->where('po.status = 1');       
        $this->db->where('mo.id = po.oid');       
        $this->db->from($this->table6 . ' mo');
        $query = $this->db->get_where($this->table4 .' po', ['po.oid'=> $oid], $limit, $offset);
        return $query->result_array();
    }
    
    public function count_finish_percoid($oid) {
        $this->db->group_by('sd.coid');            
        $this->db->select('sd.coid');
        $this->db->from($this->table5 . ' co');
        $this->db->where('co.id = sd.coid');
        $this->db->where('(select sum(`jmlsetor`) from '. $this->table2 
            . ' WHERE oid = sd.oid AND coid = sd.coid AND (finishdate != \'0000-00-00\''
            . ' OR finishdate != NULL)) >= co.jml');
        $query = $this->db->get_where($this->table2 .' sd', ['sd.oid'=> $oid]);
        return $query->result_array();
    }    
    
}
?>