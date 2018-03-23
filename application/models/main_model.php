<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main_model extends CI_Model {

    private $table = 'main_order';
    private $table2 = 'cfg_pemesan';
    private $table3 = 'cfg_pekerja';
    private $table4 = 'cutting_order';
    private $table5 = 'pretel_order';
    private $table6 = 'sewing_dist';
    private $table7 = 'cfg_boneka_performance';
    private $table8 = 'finishing_order';
    private $table9 = 'cutting_sub';
    private $table10 = 'cutting_bahan';
    private $table11 = 'cfg_ongkos';
    private $table12 = 'cfg_boneka_bahan';
    private $table13 = 'cfg_boneka_warna';
    private $table14 = 'cfg_stok_bahan';

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

    public function update_order($orderid, $form_data) {
        $this->db->where('id', $orderid);
        $status = $this->db->update($this->table, $form_data);
        return $status;
    }

    public function list_mainorder($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tglorder';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
//        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /* $search_arr = array(
          'l.waktu' => $searchtext ,
          'l.nomerhp' => $searchtext ,
          'l.pesanout' => $searchtext ,
          'l.pesanin' => $searchtext );
          $this->db->or_like($search_arr); */
        if (empty($arsip)) {
            $this->db->where(['selesai' => '0', 'cancel' => '0', 'waitingconfirm' => '0']);
        } else {
            $this->db->or_where(['selesai' => '1', 'cancel' => '1', 'waitingconfirm' => '1']);
        }
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
//        $str = $this->db->last_query();
//        return $str;
    }

    public function sql_rm_order($orderid) {
        $result = $this->db->delete($this->table, array('id' => $orderid));
        return $result;
    }

    public function get_single_order($orderid) {
        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select nomer from ' . $this->table2 . ' where id = pemesanid) as nomer', FALSE);
        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $query = $this->db->get_where($this->table, array('id' => $orderid));
        return $query->row_array();
    }

    public function list_notfinish($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tglorder';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
//        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), bahanboneka, warnaboneka) as itemjahitan', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /* $search_arr = array(
          'l.waktu' => $searchtext ,
          'l.nomerhp' => $searchtext ,
          'l.pesanout' => $searchtext ,
          'l.pesanin' => $searchtext );
          $this->db->or_like($search_arr); */
        if (empty($arsip)) {
            $this->db->where(['selesai' => '0', 'cancel' => '0', 'waitingconfirm' => '0']);
        } else {
            $this->db->or_where(['selesai' => '1', 'cancel' => '1', 'waitingconfirm' => '1']);
        }
        $query = $this->db->get_where($this->table, NULL, $limit, $offset);
        return $query->result_array();
//        $str = $this->db->last_query();
//        return $str;
    }

    public function co_notfinish($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '') {
        # cari co yg belum selesai
        $this->db->select('oid');
        $query = $this->db->get_where($this->table4, ['date' => '0000-00-00']);
        # gabung co yg blm selesai
        foreach ($query->result() as $row) {
            $nf_oid[] = $row->oid;
        }

        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'co.date';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->group_by("co.oid");
        $this->db->select('*,(select status from ' . $this->table5 . ' where oid = mo.id) as status,'
                . ' (select selesai from ' . $this->table5 . ' where oid = mo.id) as selesai,'
                . ' (select id from ' . $this->table5 . ' where oid = mo.id) as poid,'
                . ' (select p.nama from ' . $this->table5 . ' po, ' . $this->table3 . ' p where po.oid = mo.id AND p.id = po.pekerjaid) as nama', FALSE);
        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        //$this->db->select('l.waktu,l.nomerhp,l.pesanin,l.pesanout,l.konfirmasi');
        /* $search_arr = array(
          'l.waktu' => $searchtext ,
          'l.nomerhp' => $searchtext ,
          'l.pesanout' => $searchtext ,
          'l.pesanin' => $searchtext );
          $this->db->or_like($search_arr); */
        $this->db->from($this->table4 . ' co');
        if (empty($oid)) {
            $this->db->where('(co.oid = mo.id AND co.date NOT LIKE \'0000-00-00\')');
        } else {
            $this->db->where('(co.oid = ' . $oid . ' AND co.date NOT LIKE \'0000-00-00\')');
        }
        if (isset($nf_oid))
            $this->db->where_not_in('mo.id', $nf_oid);
        $query = $this->db->get_where($this->table . ' mo', ['mo.selesai' => '0', 'mo.cancel' => '', 'mo.waitingconfirm' => ''], $limit, $offset);
        return $query->result_array();
//        $str = $this->db->last_query();
//        return $str;
    }

    public function prtl_finish($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'estdate';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date desc limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
//        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
        /* $search_arr = array(
          'l.waktu' => $searchtext ,
          'l.nomerhp' => $searchtext ,
          'l.pesanout' => $searchtext ,
          'l.pesanin' => $searchtext );
          $this->db->or_like($search_arr); */
        $this->db->from($this->table5 . ' po');
        if (empty($oid)) {
            $this->db->where('po.oid = mo.id AND po.status = 1');
        } else {
            $this->db->where('po.oid = ' . $oid . ' AND po.status = 1');
        }
//        $where = ['selesai'=>'0','cancel'=>'','waitingconfirm'=>'', 'po.status' => '1'];
//        $query = $this->db->get_where($this->table .' mo', NULL, $limit, $offset);
        $query = $this->db->get_where($this->table . ' mo', ['mo.selesai' => '0', 'mo.cancel' => '', 'mo.waitingconfirm' => ''], $limit, $offset);
        return $query->result_array();
    }

    public function so_finish($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tglorder';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);

        $this->db->select('*,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date desc limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
//        $this->db->select('*,(SELECT SUM(jmlsetor) FROM '.$this->table6.' WHERE oid = mo.id) as total', FALSE);
        /* $search_arr = array(
          'l.waktu' => $searchtext ,
          'l.nomerhp' => $searchtext ,
          'l.pesanout' => $searchtext ,
          'l.pesanin' => $searchtext );
          $this->db->or_like($search_arr); */
//        $this->db->from($this->table6 . ' sd');
        if (empty($oid)) {
//            $this->db->where('sd.oid = mo.id AND sd.status = 1');
            $this->db->where('(SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' WHERE oid = mo.id) >= mo.jumlah');
        } else {
//            $this->db->where('sd.oid = '.$oid.' AND sd.status = 1');            
            $this->db->where('(SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' WHERE oid = ' . $oid . ') >= mo.jumlah');
        }
//        $where = ['selesai'=>'0','cancel'=>'','waitingconfirm'=>'', 'po.status' => '1'];
//        $query = $this->db->get_where($this->table .' mo', NULL, $limit, $offset);
        $query = $this->db->get_where($this->table . ' mo', ['mo.selesai' => '0', 'mo.cancel' => '', 'mo.waitingconfirm' => ''], $limit, $offset);
        return $query->result_array();
    }

    public function control_list($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'estdate';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('*,(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
//        $this->db->select('IFNULL((select date from ' . $this->table4 . ' where oid = mo.id order by date desc limit 1),0) as cutdate', FALSE);
//        $this->db->select('IFNULL((select minperform from ' . $this->table7 . ' where id = mo.itemid),0) as minperform', FALSE);
//        $this->db->select('CEIL(jumlah/(select minperform from ' . $this->table7 . ' where id = mo.itemid))+2 as days',FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date desc limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
//        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        /* $search_arr = array(
          'l.waktu' => $searchtext ,
          'l.nomerhp' => $searchtext ,
          'l.pesanout' => $searchtext ,
          'l.pesanin' => $searchtext );
          $this->db->or_like($search_arr); */
        if (empty($arsip)) {
            $where = ['mo.selesai' => '0', 'mo.cancel' => '0', 'mo.waitingconfirm' => '0'];
        } else {
            $where = ("(mo.selesai = '1' OR mo.cancel = '1' OR mo.waitingconfirm = '1')");
        }
        $query = $this->db->get_where($this->table . ' mo', $where, $limit, $offset);
        return $query->result_array();
//        $str = $this->db->last_query();
//        return $str;
    }

    public function item_perbln($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tglorder';
        } else {
            $order = $orderby;
        }
        $this->db->group_by('itemid,warnaboneka,bahanboneka');
        $this->db->order_by($order, $asc);
        $this->db->select('warnaboneka,bahanboneka,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('SUM(jumlah) as jml');
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('tglorder BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function item_percust($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'jml';
        } else {
            $order = $orderby;
        }
        $this->db->group_by('pemesanid,itemid,warnaboneka,bahanboneka');
        $this->db->order_by($order, $asc);
        $this->db->select('(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
        $this->db->select('warnaboneka,bahanboneka,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('SUM(jumlah) as jml');
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('tglorder BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function top_ten($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'jml';
        } else {
            $order = $orderby;
        }
        $this->db->group_by('pemesanid');
        $this->db->order_by($order, $asc);
        $this->db->select('(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
        $this->db->select('SUM(jumlah) as jml');
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('tglorder BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function co_finish_only($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'itemjahitan';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        if ($order != 'itemjahitan') {
            $this->db->order_by('co.oid');
            $this->db->select('co.*,mo.warnaboneka,mo.bahanboneka');
            $this->db->select('(select status from ' . $this->table5 . ' where oid = co.oid AND coid = co.id) as status', FALSE);
            $this->db->select('(select selesai from ' . $this->table5 . ' where oid = co.oid AND coid = co.id) as selesai', FALSE);
            $this->db->select('(select p.nama from ' . $this->table5 . ' po, ' . $this->table3 . ' p where po.oid = co.oid AND po.coid = co.id AND p.id = po.pekerjaid) as nama', FALSE);
            $this->db->select('(select id from ' . $this->table5 . ' where oid = co.oid AND coid = co.id) as poid', FALSE);
        } else {
            $this->db->group_by("co.oid");
            $this->db->select('co.*,mo.warnaboneka,mo.bahanboneka');
        }
        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->from($this->table4 . ' co');
        if (empty($oid)) {
            if ($order != 'itemjahitan') {
                $this->db->where('(mo.id = co.oid AND co.date NOT LIKE \'0000-00-00\')');
            } else {
                $this->db->where('(co.oid = mo.id AND co.date NOT LIKE \'0000-00-00\')');
            }
        } else {
            $this->db->where('(co.oid = ' . $oid . ' AND co.date NOT LIKE \'0000-00-00\')');
        }
        $query = $this->db->get_where($this->table . ' mo', ['mo.selesai' => '0', 'mo.cancel' => '', 'mo.waitingconfirm' => ''], $limit, $offset);
        return $query->result_array();
    }

    public function co_finish_only2($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'date';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
//        if($order != 'itemjahitan'){
//            $this->db->order_by('co.oid');
//            $this->db->select('co.*,mo.warnaboneka,mo.bahanboneka');
//            $this->db->select('(select status from ' . $this->table5 . ' where oid = co.oid AND coid = co.id) as status', FALSE);
//            $this->db->select('(select selesai from ' . $this->table5 . ' where oid = co.oid AND coid = co.id) as selesai', FALSE);
//            $this->db->select('(select p.nama from ' . $this->table5 . ' po, ' . $this->table3 . ' p where po.oid = co.oid AND po.coid = co.id AND p.id = po.pekerjaid) as nama', FALSE);
//            $this->db->select('(select id from ' . $this->table5 . ' where oid = co.oid AND coid = co.id) as poid', FALSE);
//        } else {
        $this->db->group_by("co.oid");
        $this->db->select('co.*,mo.warnaboneka,mo.bahanboneka');
        $this->db->select('(select date from ' . $this->table4 . ' where oid = mo.id ORDER BY date ' . $asc . ' limit 1) as date', FALSE);
        $this->db->select('(select jml from ' . $this->table4 . ' where oid = mo.id ORDER BY jml ' . $asc . ' limit 1) as jml', FALSE);
        $this->db->select('(select selesai from ' . $this->table5 . ' where oid = mo.id ORDER BY selesai ' . $asc . ' limit 1) as selesai', FALSE);
//        }
//        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->from($this->table4 . ' co');
        if (empty($oid)) {
//            if($order != 'itemjahitan'){
            $this->db->where('(mo.id = co.oid AND co.date NOT LIKE \'0000-00-00\')');
//            } else {
//                $this->db->where('(co.oid = mo.id AND co.date NOT LIKE \'0000-00-00\')');                
//            }
        } else {
            $this->db->where('(co.oid = ' . $oid . ' AND co.date NOT LIKE \'0000-00-00\')');
        }
        if (empty($arsip)) {
            $where = ['mo.selesai' => '0', 'mo.cancel' => '0', 'mo.waitingconfirm' => '0'];
        } else {
            $where = ("(mo.selesai = '1' OR mo.cancel = '1' OR mo.waitingconfirm = '1')");
        }
        $query = $this->db->get_where($this->table . ' mo', $where, $limit, $offset);
        return $query->result_array();
    }

    public function prtl_finish_only($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'estdate';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->group_by("po.oid");
//        $this->db->select('*,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('*');
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
//            . $this->table4 . ' where oid = mo.id AND id = po.coid order by date desc limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from ' 
                . $this->table4 . ' where oid = mo.id order by date ' . $asc . ' limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
//        $this->db->select('(select jml from ' . $this->table4 . ' where oid = po.oid AND id = po.coid) as jumlah', FALSE);
        $this->db->select('(select jml from ' . $this->table4 . ' where oid = po.oid order by jml ' . $asc . ' limit 1) as jumlah', FALSE);
        $this->db->from($this->table5 . ' po');
        if (empty($oid)) {
            $this->db->where('po.oid = mo.id AND po.status = 1');
        } else {
            $this->db->where('po.oid = ' . $oid . ' AND po.status = 1');
        }
        if (empty($arsip)) {
            $where = ['mo.selesai' => '0', 'mo.cancel' => '0', 'mo.waitingconfirm' => '0'];
        } else {
            $where = ("(mo.selesai = '1' OR mo.cancel = '1' OR mo.waitingconfirm = '1')");
        }
        $query = $this->db->get_where($this->table . ' mo', $where, $limit, $offset);
        return $query->result_array();
    }

    public function so_finish_only($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'estdate';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->group_by("sd.oid");
        $this->db->select('sd.oid,mo.aksesoris,mo.jenistulisan,mo.tulisan,mo.lainlain');
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date ' . $asc . ' limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);                
        $this->db->select('(select jml from ' . $this->table4 . ' WHERE id = sd.coid AND oid = sd.oid '
                . ' order by jml ' . $asc . ' limit 1) as jumlah', FALSE);
        $this->db->from($this->table6 . ' sd');
        if (empty($oid)) {
            $this->db->where('(SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' WHERE oid = sd.oid AND coid = sd.coid AND finishdate NOT LIKE \'0000-00-00\') >= (SELECT jml FROM '.$this->table4.' WHERE id = sd.coid AND oid = sd.oid)');
        } else {
            $this->db->where('(SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' WHERE oid = ' . $oid . ' AND coid = sd.coid AND finishdate NOT LIKE \'0000-00-00\') >= (SELECT jml FROM '.$this->table4.' WHERE id = sd.coid AND oid = sd.oid)');
        }
        $this->db->where('mo.id = sd.oid');
        if (empty($arsip)) {
            $where = ['mo.selesai' => '0', 'mo.cancel' => '0', 'mo.waitingconfirm' => '0'];
        } else {
            $where = ("(mo.selesai = '1' OR mo.cancel = '1' OR mo.waitingconfirm = '1')");
        }
        $query = $this->db->get_where($this->table . ' mo', $where, $limit, $offset);
        if(empty($limit)){
            return $query->num_rows();
        } else {
            return $query->result_array();
        }        
    }

    public function so_finish_only_slow($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $oid = '', $arsip = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'estdate';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->group_by("sd.oid");
/*		$arsip2 = empty($arsip) ? NULL : $arsip;
		$oid2 = empty($oid) ? NULL : $oid;
          $stored_procedure = "CALL so_finonly(?,?,?,?,?,?) ";
          $query = $this->db->query($stored_procedure,['arsip'=>$arsip2, 'orderby'=>$order,
              'asc'=>$asc, 'limit'=>$limit, 'offset'=>$offset, 'oid'=>$oid2]);
          return $query->result_array();
*/        
//#        $this->db->select('*,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
//        $this->db->select('co.*,mo.aksesoris,mo.jenistulisan,mo.tulisan,mo.lainlain,co.jml as jumlah');
        $this->db->select('co.oid,mo.aksesoris,mo.jenistulisan,mo.tulisan,mo.lainlain');
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date ' . $asc . ' limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
                
//#        $this->db->select('(select jml from ' . $this->table4 . ' where oid = sd.oid order by jml '.$asc.' limit 1) as jumlah', FALSE);
//#        $this->db->select('(select jml from ' . $this->table6 . ' sd1, '.$this->table4
//#                .' co1 where sd1.oid = sd.oid AND co1.id = sd.coid AND co1.jml <= (SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' sd2, '.$this->table4 .' co2 WHERE sd2.oid = sd.oid AND co2.id = sd.coid) '
//#                . 'order by jml '.$asc.' limit 1) as jumlah', FALSE);
        $this->db->select('(select jml from ' . $this->table6 . ' sd1, ' . $this->table4
                . ' co1 where co1.id = sd1.coid AND sd1.oid = sd.oid AND sd1.finishdate NOT LIKE \'0000-00-00\' '
                . ' order by jml ' . $asc . ' limit 1) as jumlah', FALSE);
        $this->db->from($this->table6 . ' sd');
        $this->db->from($this->table4 . ' co');
        if (empty($oid)) {
            $this->db->where('(SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' WHERE oid = mo.id AND coid = sd.coid AND finishdate NOT LIKE \'0000-00-00\') >= co.jml');
//            $this->db->where('sd_jmlsetor(mo.id,sd.coid) >= co.jml');
        } else {
            $this->db->where('(SELECT SUM(jmlsetor) FROM ' . $this->table6 . ' WHERE oid = ' . $oid . ' AND coid = sd.coid AND finishdate NOT LIKE \'0000-00-00\') >= co.jml');
//            $this->db->where('sd_jmlsetor(' . $oid . ',sd.coid) >= co.jml');
        }
        $this->db->where('(co.oid = sd.oid AND co.id = sd.coid)');
        if (empty($arsip)) {
            $where = ['mo.selesai' => '0', 'mo.cancel' => '0', 'mo.waitingconfirm' => '0'];
        } else {
            $where = ("(mo.selesai = '1' OR mo.cancel = '1' OR mo.waitingconfirm = '1')");
        }
        $query = $this->db->get_where($this->table . ' mo', $where, $limit, $offset);
        if(empty($limit)){
            return $query->num_rows();
        } else {
            return $query->result_array();
        }        
    }

    public function rm_all_data_by_oid($orderid) {
        $result1 = $this->db->delete($this->table4, array('oid' => $orderid));
        $result2 = $this->db->delete($this->table9, array('oid' => $orderid));
        $result3 = $this->db->delete($this->table10, array('oid' => $orderid));
        $result4 = $this->db->delete($this->table5, array('oid' => $orderid));
        $result5 = $this->db->delete($this->table6, array('oid' => $orderid));
        $result6 = $this->db->delete($this->table8, array('oid' => $orderid));
        if ($result1 && $result2 && $result3 && $result4 && $result5 && $result6) {
            return TRUE;
        }
        return FALSE;
    }

    public function rm_all_data_by_coid($orderid, $coid) {
        $where = ['oid' => $orderid, 'coid' => $coid];
        $result1 = $this->db->delete($this->table5, $where);
        $result2 = $this->db->delete($this->table6, $where);
        $result3 = $this->db->delete($this->table8, $where);
        if ($result1 && $result2 && $result3) {
            return TRUE;
        }
        return FALSE;
    }

    public function item_bydate($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '', $field = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tglorder';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('id,tglorder,targetdate,tglkirim', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date ' . $asc . ' limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $sel_field = (!empty($field)) ? $field : 'tglorder';
            $this->db->where($sel_field . ' BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function cut_pertulisan($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '', $jenis = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'jml';
        } else {
            $order = $orderby;
        }
//        $this->db->group_by('cs.kegiatan');
        $this->db->order_by($order, $asc);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('mo.id,mo.jenistulisan as jenis,mo.tulisan as kata,mo.jumlah as total');
//        $this->db->select('SUM(cs.jumlah) as jml');
        $this->db->select('(SELECT jumlah FROM ' . $this->table9 . ' WHERE oid = mo.id ORDER BY jumlah ASC LIMIT 1) as jml');
        if (!empty($searchtext)) {
            $search_arr = [ 'mo.tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('mo.tglorder BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
//        $this->db->from($this->table9 . ' cs');
//        $this->db->where('cs.oid = mo.id AND mo.jenistulisan = \''.$jenis.'\'');         
        $this->db->where('mo.jenistulisan = \'' . $jenis . '\'');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function blm_lunas($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'nama';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('(select nama from ' . $this->table2 . ' where id = pemesanid) as nama, (select concat("Jenis: ",jeniskontak,"\nNomer: ",nomer,"\nAlamat: ",alamat) from ' . $this->table2 . ' where id = pemesanid) as title', FALSE);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('id,jumlah as jml,tglorder,lainlain,tgldp,jmldp');
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('tglorder BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $this->db->where('tglpelunasan LIKE \'0000-00-00\' AND jmlpelunasan <= 0');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function sew_nilai($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '', $staff = '', $sum = '', $total = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'itemjahitan';
        } else {
            $order = $orderby;
        }
        if (!empty($sum)) {
            $this->db->group_by('mo.itemid');
            $this->db->select('SUM(ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) as total', FALSE);
            $this->db->select('COUNT(mo.itemid) as record', FALSE);
        }
        if (!empty($total)) {
            $this->db->group_by('sd.pekerjaid');
            $this->db->select('SUM(sd.jmlsetor * (IF((ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) > bp.minperform,(o.ongkosjahit + bp.bonus),'
                    . 'IF((ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) < bp.minperform,(o.ongkosjahit - bp.penalty),o.ongkosjahit)))) AS total', FALSE);
        }
        $this->db->order_by($order, $asc);
        $this->db->select('sd.*,bp.*');
        $this->db->select('CONCAT(bp.itemjahitan,\' \',mo.bahanboneka,\' \',mo.warnaboneka,\' (oid: \',sd.oid,\'.\',sd.coid,\')\') AS namaitem', FALSE);
        $this->db->select('TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1 AS hari', FALSE);
        $this->db->select('ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1)) AS prestasi', FALSE);
//        $this->db->select('(select itemjahitan from ' . $this->table7 . ' where id = mo.itemid) as itemjahitan', FALSE);
        $this->db->select('IF((ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) > bp.minperform,(o.ongkosjahit + bp.bonus),'
                . 'IF((ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) < bp.minperform,(o.ongkosjahit - bp.penalty),o.ongkosjahit) ) AS ongkos', FALSE);
        $this->db->select('sd.jmlsetor * (IF((ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) > bp.minperform,(o.ongkosjahit + bp.bonus),'
                . 'IF((ROUND(sd.jmlsetor/(TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1))) < bp.minperform,(o.ongkosjahit - bp.penalty),o.ongkosjahit) )) AS subtotal', FALSE);
        if (!empty($searchtext)) {
            $search_arr = [ 'mo.tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('sd.startdate BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        if (!empty($staff)) {
            $this->db->where('sd.pekerjaid = \'' . $staff . '\'');
        }
        $this->db->where('mo.id = sd.oid AND bp.id = mo.itemid');
        $this->db->where('o.jahitanid = mo.itemid');
        $this->db->where('sd.finishdate != \'0000-00-00\'');
        $this->db->from($this->table6 . ' sd');
        $this->db->from($this->table7 . ' bp');
        $this->db->from($this->table11 . ' o');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function prtl_ongkos($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '', $staff = '', $total = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'itemjahitan';
        } else {
            $order = $orderby;
        }

        if (!empty($total)) {
            $this->db->group_by('sd.pekerjaid');
            $this->db->select('SUM(co.jml * o.ongkospretel) AS total', FALSE);
        }
        $this->db->order_by($order, $asc);
        $this->db->select('sd.*,bp.*,co.jml as jmlsetor');
        $this->db->select('CONCAT(bp.itemjahitan,\' \',mo.bahanboneka,\' \',mo.warnaboneka,\' (oid: \',sd.oid,\'.\',sd.coid,\')\') AS itemjahitan', FALSE);
        $this->db->select('o.ongkospretel AS ongkos', FALSE);
        $this->db->select('(co.jml * o.ongkospretel) AS subtotal', FALSE);
        if (!empty($searchtext)) {
            $search_arr = [ 'mo.tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('sd.selesai BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        if (!empty($staff)) {
            $this->db->where('sd.pekerjaid = \'' . $staff . '\'');
        }
        $this->db->where('mo.id = sd.oid AND bp.id = mo.itemid');
        $this->db->where('o.jahitanid = mo.itemid AND co.id = sd.coid');
        $this->db->from($this->table4 . ' co');
        $this->db->from($this->table5 . ' sd');
        $this->db->from($this->table7 . ' bp');
        $this->db->from($this->table11 . ' o');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function fin_ongkos($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '', $staff = '', $total = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'itemjahitan';
        } else {
            $order = $orderby;
        }

        if (!empty($total)) {
            $this->db->group_by('sd.pekerjaid');
            $this->db->select('SUM(sd.selesai * o.ongkosfinishing) AS total', FALSE);
        }
        $this->db->order_by($order, $asc);
        $this->db->select('sd.*,bp.*,sd.selesai as jmlsetor');
        $this->db->select('CONCAT(bp.itemjahitan,\' \',mo.bahanboneka,\' \',mo.warnaboneka,\' (oid: \',sd.oid,\'.\',sd.coid,\')\') AS itemjahitan', FALSE);
        $this->db->select('o.ongkosfinishing AS ongkos', FALSE);
        $this->db->select('(sd.selesai * o.ongkosfinishing) AS subtotal', FALSE);
        if (!empty($searchtext)) {
            $search_arr = [ 'mo.tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('sd.tanggal BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        if (!empty($staff)) {
            $this->db->where('sd.pekerjaid = \'' . $staff . '\'');
        }
        $this->db->where('mo.id = sd.oid AND bp.id = mo.itemid');
        $this->db->where('o.jahitanid = mo.itemid');
        $this->db->from($this->table8 . ' sd');
        $this->db->from($this->table7 . ' bp');
        $this->db->from($this->table11 . ' o');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function avg_bhn($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'tglorder';
        } else {
            $order = $orderby;
        }
        $this->db->group_by('itemid,jenis,warna');
        $this->db->order_by($order, $asc);
        $this->db->select('cb.*,(select itemjahitan from ' . $this->table7 . ' where id = itemid) as itemjahitan', FALSE);
        $this->db->select('(select warna from ' . $this->table13 . ' where id = cb.warna) as color', FALSE);
        $this->db->select('(select bahan from ' . $this->table12 . ' where id = jenis) as bahan', FALSE);
        if (!empty($start) && !empty($stop)) {
            $this->db->select('(SELECT sum(mo3.jumlah) FROM '. $this->table .' mo3 WHERE mo3.id IN '
                . '(SELECT mo2.id FROM ('. $this->table10 .' cb2, '. $this->table .' mo2) WHERE mo2.tglorder '
                . 'BETWEEN \'' . $start . '\' AND \'' . $stop . '\' AND mo2.id = cb2.oid '
                . 'AND mo2.itemid = mo.itemid AND cb2.jenis = cb.jenis '
                . 'AND cb2.warna = cb.warna group by mo2.id))/SUM(panjang) as ratarata', FALSE);
        }
//        $this->db->select('mo.jumlah/SUM(panjang) as ratarata');
//        $this->db->select('SUM(panjang)/SUM(mo.jumlah) as ratarata');
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('mo.tglorder BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $this->db->where('mo.id = cb.oid');
        $this->db->from($this->table10 . ' cb');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function stok_bhn($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'bahan';
        } else {
            $order = $orderby;
        }
        $this->db->group_by('bahan,color');
        $this->db->order_by($order, $asc);
        $this->db->select('sb.*', FALSE);
        $this->db->select('(select warna from ' . $this->table13 . ' where id = sb.warnaid) as color', FALSE);
        $this->db->select('(select bahan from ' . $this->table12 . ' where id = sb.bahanid) as bahan', FALSE);
        $this->db->select('SUM(masuk) as masuk');
        $this->db->select('(select SUM(panjang) from ' . $this->table10 . ' where jenis = sb.bahanid AND warna = sb.warnaid) as pemakaian', FALSE);
        $this->db->select('(SUM(masuk) - IFNULL((select SUM(panjang) from ' . $this->table10 . ' where jenis = sb.bahanid AND warna = sb.warnaid),0)) as sisa', FALSE);
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        $query = $this->db->get_where($this->table14 . ' sb', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function item_inweek($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '', $field = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'estdate';
        } else {
            $order = $orderby;
        }
        $this->db->order_by($order, $asc);
        $this->db->select('CONCAT_WS(\' \',(select itemjahitan from ' . $this->table7 . ' where id = itemid), mo.bahanboneka, mo.warnaboneka) as itemjahitan', FALSE);
        $this->db->select('id,jumlah as jml', FALSE);
        $this->db->select('IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                . $this->table4 . ' where oid = mo.id order by date ' . $asc . ' limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate) as estdate', FALSE);
        if (!empty($searchtext)) {
            $search_arr = [ 'tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $sel_field = (!empty($field)) ? $field : 'tglorder';
            $sel_field = ($sel_field == 'estdate') ? 'IF(targetdate = NULL OR targetdate = \'0000-00-00\',DATE_ADD((IFNULL((select date from '
                    . $this->table4 . ' where oid = mo.id order by date ' . $asc . ' limit 1),0)), INTERVAL (CEIL(jumlah/(select minperform from '
                    . $this->table7 . ' where id = mo.itemid))+2) DAY),targetdate)' : $sel_field;
            $this->db->where($sel_field . ' BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
        $this->db->where('(tglkirim = NULL OR tglkirim = \'0000-00-00\')');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }

    public function cmt_progress($slug = FALSE, $limit = NULL, $searchtext = '', $orderby = NULL, $asc = 'desc', $start = '', $stop = '') {
        $offset = $slug;
        if ($orderby == NULL || $orderby == '0') {
            $order = 'startdate';
        } else {
            $order = $orderby;
        }

        $this->db->order_by($order, $asc);
        $this->db->select('mo.id,sd.startdate, sd.coid, co.jml as jml');
        $this->db->select('IF(sd.finishdate = \'0000-00-00\', 0, sd.jmlsetor) as jmlsetor', FALSE);
        $this->db->select('CONCAT_WS(\' \',(SELECT itemjahitan FROM ' . $this->table7 . ' WHERE id = mo.itemid), mo.bahanboneka, mo.warnaboneka, CONCAT(\'(oid: \',sd.oid,\'.\',sd.coid,\')\')) AS itemjahitan', FALSE);
        $this->db->select('IF(sd.finishdate NOT LIKE \'0000-00-00\', TIMESTAMPDIFF(DAY,sd.startdate,sd.finishdate)+1, TIMESTAMPDIFF(DAY,sd.startdate,CURDATE())+1) as progress', FALSE);
        $this->db->select('IF(sd.finishdate NOT LIKE \'0000-00-00\', CEIL(sd.jmlsetor/bp.minperform), CEIL(co.jml/bp.minperform)) as target', FALSE);
//        $this->db->select('CEIL(co.jml/bp.minperform) AS target', FALSE);
        $this->db->select('(SELECT nama FROM ' . $this->table3 . ' WHERE id = sd.pekerjaid) AS nama', FALSE);

        if (!empty($searchtext)) {
            $search_arr = [ 'mo.tglorder' => $searchtext];
            $this->db->or_like($search_arr);
        }
        if (!empty($start) && !empty($stop)) {
            $this->db->where('sd.startdate BETWEEN \'' . $start . '\' AND \'' . $stop . '\'');
        }
//        $this->db->where('(sd.finishdate = NULL OR sd.finishdate = \'0000-00-00\')');
        $this->db->where('mo.id = sd.oid AND bp.id = mo.itemid AND (co.id = sd.coid AND co.oid = sd.oid)');
        $this->db->from($this->table4 . ' co');
        $this->db->from($this->table6 . ' sd');
        $this->db->from($this->table7 . ' bp');
        $query = $this->db->get_where($this->table . ' mo', NULL, $limit, $offset);
        return $query->result_array();
    }
    
}
?>