<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fin extends CI_Controller {

    private $ctlr = "fin";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['pagination', 'session', 'form_validation', 'activitylog']);
        $this->load->model(['fin_model', 'pekerja_model', 'main_model', 'cut_model', 'sew_model', 'item_model']);
        $this->load->library('unread');
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl', 'url_helper'));        
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));

        // Redirect unauthenticated users to signin page
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . 'fin'));
        }
    }

    function insert() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_fo', 'create_fo'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'ad' . $this->ctlr;
        $data['success'] = '';
        $data['mode'] = 'insert';

        $orderid = $this->input->post("oid");
        $dataid = $this->input->post("dataid");
        $coid = $this->input->post("coid");
 
        $data['data2'] = $this->cut_model->get_single_data($coid);
        $data['options'] = $this->get_options();

//            if(!empty($pekerjaid)){
//		$this->form_validation->set_rules('search_nama', 'Pekerja', 'required');			
        $this->form_validation->set_rules('pekerjaid', 'Pekerja', 'required');
        $this->form_validation->set_rules('selesai', 'Selesai', 'is_numeric|max_length[11]|required');
        $this->form_validation->set_rules('tgl', 'Tgl. Update', '');
        $this->form_validation->set_rules('oid', 'Order ID', '');
        $this->form_validation->set_rules('coid', 'Cutting Order ID', '');

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
//            }                
        if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
            if (!empty($dataid)) {
                $data['hidden'] = array('dataid' => $dataid, 'coid' => $coid);
                $data['mode'] = 'edit';
            } else {
                $data['hidden'] = ['coid' => $coid];
            }

            $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
        } else { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'oid' => set_value('oid'),
                'coid' => set_value('coid'),
                'tanggal' => set_value('tgl'),
                'pekerjaid' => set_value('pekerjaid'),
                'selesai' => set_value('selesai'),
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_fo'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->fin_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Penambahan data berhasil');
                    $form_data['id'] = $this->activitylog->get_id('finishing_order');
                    $this->activitylog->save_log($data['account'], $this->ctlr, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', 'Penambahan data gagal');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_fo'])) {
                    redirect('home/blocked');
                }
                $this->fin_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', "Data berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->ctlr, 'update', $form_data);                
            }

//            $this->update_mo($orderid);
//            $this->load->view($this->ctlr . '/index', isset($data) ? $data : NULL);
//            $this->session->set_flashdata('oid', $orderid);
            $this->session->set_userdata('oid', $orderid);
            $this->session->set_userdata('coid', $coid);
            redirect($this->ctlr . '/set');   // or whatever logic needs to occur
        }
    }

    function index($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_fo'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');

        # set session for search purpose 
        $search_session = $this->session->userdata('searchtext');
        $search_post = $this->input->post('searchtext');
        $status_post = $this->input->post('status');
        $searchtext = ($status_post) ? $search_post : $search_session;
        $this->session->set_userdata('searchtext', $searchtext);

        # pagination setup
        $orderby = urldecode($this->uri->segment(3, 0));
        $asc = urldecode($this->uri->segment(4, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'desc' : $asc;
        $rows = $this->main_model->so_finish_only($slug, NULL, $searchtext, $orderby, $asc);
        $this->pagination->base_url = site_url($this->ctlr . '/');
//        $this->pagination->total_rows = count($rows);
        $this->pagination->total_rows = $rows;
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;
        $data['focoasc'] = ($orderby != 'itemjahitan') ? $asc : 'desc';
        $data['focoorderby'] = (!empty($orderby) && $orderby != 'itemjahitan') ? $orderby : NULL;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->so_finish_only($slug, $limit, $searchtext, $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        //$this->load->view('header', $data);
        $this->load->view($this->ctlr . '/index', $data);
        //$this->load->view('footer');
    }

    public function del($slug = NULL) {
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_fo'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $mainorder = $this->fin_model->get_single_data($dataid);
        $s_oid = $this->session->userdata('oid');
        $oid = (empty($mainorder['oid'])) ? $s_oid : $mainorder['oid'];
        $data['success'] = '';
        if (!empty($mainorder['oid'])) {
            $result = $this->fin_model->sql_rm_data($dataid);
            if ($result) {
                $data['success'] = "Tugas " . $mainorder['nama'] . " sukses dihapus";
//                $this->update_mo($orderid);
                $this->activitylog->save_log($this->account, $this->ctlr, 'delete', $mainorder);
            } else {
                $data['success'] = "Tugas " . $mainorder['nama'] . " gagal dihapus";
            }
        }
        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/set');   // or whatever logic needs to occur                        
    }

    public function set($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_fo'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $s_oid = $this->session->userdata('oid');
        $s_coid = $this->session->userdata('coid');
        $orderid = (!empty($this->input->post("oid"))) ? $this->input->post("oid") : $s_oid;
        $coid = (!empty($this->input->post("coid"))) ? $this->input->post("coid") : $s_coid;

        $data['title'] = 'sd' . $this->ctlr;
        $data['hidden'] = array('orderid' => $orderid, 'coid' => $coid);

        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $asc = (empty($asc)) ? 'desc' : $asc;

        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
//        $data['data'] = $this->main_model->get_single_order($orderid);        
        $data['data'] = $this->cut_model->get_single_data($coid);
        $data['attributes'] = array('role' => 'form', 'style' => 'margin:auto;');
        $data['records'] = $this->fin_model->get_data_by_oid($orderid, FALSE, NULL, '', $orderby, $asc, $coid);
        $data['mode'] = 'edit';
        $data['pagination'] = $this->pagination->create_links();
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";
        $this->session->set_userdata('oid', $orderid);
        $this->session->set_userdata('coid', $coid);

        $this->load->view($this->ctlr . '/childindex', isset($data) ? $data : NULL);
    }

    public function edit() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_fo'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $orderid = $this->input->post("oid");
        $coid = $this->input->post("coid");
//        $data['data2'] = $this->main_model->get_single_order($orderid);        
        $data['data2'] = $this->cut_model->get_single_data($coid);
        $data['options'] = $this->get_options();

        $data['title'] = 'ed' . $this->ctlr;
        $data['hidden'] = array('dataid' => $dataid, 'coid' => $coid);

        $data['data'] = $this->fin_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';

        $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
    }

    public function json() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
        }

        $orderid = $this->input->post("oid");
        $dataid = $this->input->post("pk");
        $value = $this->input->post("value");

        $form_data = array(
            'oid' => $orderid,
            'target' => $value,
        );

        if (empty($dataid)) {
            $lastid = $this->fin_model->SaveForm($form_data);
            if ($lastid == FALSE) {
                header('HTTP/1.0 400 Bad Request', true, 400);
                echo "Update gagal, silahkan di coba kembali!";
            } else {
                echo json_encode(['id' => $lastid]);
            }
        } else {
            if (empty($value)) {
                $this->fin_model->sql_rm_data($dataid);
                echo json_encode(['up' => $dataid]);
            } else {
                $this->fin_model->update_data($dataid, $form_data);
                echo json_encode(['id' => $dataid]);
            }
        }
    }

    private function update_mo($orderid) {
        ### Jika jumlah selesai sama dengan main_order, update status main_order ###
        $tgl_data = $this->fin_model->get_data_by_oid($orderid, FALSE, 1, '', 'tanggal', 'desc');
        $sum_data = $this->fin_model->sum_data_by_oid($orderid, '','selesai');
        $mo_data = $this->main_model->get_single_order($orderid);

        if ($sum_data['selesai'] >= $mo_data['jumlah']) {
//            $this->main_model->update_order($orderid, ['selesai'=>'1', 'tglkirim'=> $tgl_data[0]['tanggal']]);
            $this->main_model->update_order($orderid, ['selesai' => '1']);
        } else {
//            $this->main_model->update_order($orderid, ['selesai'=>'0', 'tglkirim'=>'']);                                
            $this->main_model->update_order($orderid, ['selesai' => '0']);
        }
    }

    function arsip($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_fo'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');

        # set session for search purpose 
        $search_session = $this->session->userdata('searchtext');
        $search_post = $this->input->post('searchtext');
        $status_post = $this->input->post('status');
        $searchtext = ($status_post) ? $search_post : $search_session;
        $this->session->set_userdata('searchtext', $searchtext);

        # pagination setup
        $funct = urldecode($this->uri->segment(2, 0));
        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'desc' : $asc;
        $arsip = 'arsip';
        $rows = $this->main_model->so_finish_only($slug, NULL, $searchtext, $orderby, $asc, '', $arsip);
        $this->pagination->base_url = site_url($this->ctlr . "/$funct/");
//        $this->pagination->base_url = site_url($this->ctlr . '/');
//        $this->pagination->total_rows = count($rows);
        $this->pagination->total_rows = $rows;
        $this->pagination->uri_segment = 3;
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'r' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;
        $data['arsip'] = $arsip;
        $data['focoasc'] = ($orderby != 'itemjahitan') ? $asc : 'desc';
        $data['focoorderby'] = (!empty($orderby) && $orderby != 'itemjahitan') ? $orderby : NULL;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->so_finish_only($slug, $limit, $searchtext, $orderby, $asc, '', $arsip);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/index', $data);
    }

    private function get_options() {
        $items = $this->pekerja_model->json_data();
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['name'];
        }
        return $options;
    }

}
