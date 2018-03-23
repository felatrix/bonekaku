<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prtl extends CI_Controller {

    private $ctlr = "prtl";
    private $text = "Pretel Order";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['pagination', 'session', 'form_validation', 'activitylog']);
        $this->load->model(['prtl_model', 'pekerja_model', 'main_model', 'cut_model']);
        $this->load->library('unread');
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl', 'url_helper'));        
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
    }

    function insert() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_po', 'create_po'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'aprtl';
        $data['judul'] = '<h3>' . $this->text . ' &rarr; Input</h3>';
        $data['success'] = '';
        $data['mode'] = 'insert';

        $orderid = $this->input->post("oid");
        $dataid = $this->input->post("dataid");
        $coid = $this->input->post("coid");
        $data['data2'] = $this->main_model->get_single_order($orderid);
        $data['data3'] = $this->cut_model->get_single_data($coid);
        $data['options'] = $this->get_options();

//            if(!empty($pekerjaid)){
//		$this->form_validation->set_rules('search_nama', 'Pekerja', 'required');			
        $this->form_validation->set_rules('pekerjaid', 'Pekerja', 'required');
        $this->form_validation->set_rules('status', 'Status', '');
        $this->form_validation->set_rules('tgl', 'Tgl. Selesai', '');
        $this->form_validation->set_rules('oid', 'Order ID', '');
        $this->form_validation->set_rules('coid', 'CO ID', '');

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
                'status' => set_value('status'),
                'pekerjaid' => set_value('pekerjaid'),
                'selesai' => set_value('tgl'),
                'coid' => set_value('coid'),
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_po'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->prtl_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Penambahan tugas berhasil');
                    $form_data['id'] = $this->activitylog->get_id('pretel_order');
                    $this->activitylog->save_log($data['account'], $this->ctlr, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', 'Penambahan tugas gagal');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_po'])) {
                    redirect('home/blocked');
                }
                $this->prtl_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', "Tugas berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->ctlr, 'update', $form_data);                
            }
            redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
        }
    }

    function index($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_po'])) {
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
        $rows = $this->main_model->co_finish_only2($slug, NULL, $searchtext, $orderby, $asc);
        $this->pagination->base_url = site_url($this->ctlr . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'lprtl';
        $data['judul'] = '<h3>' . $this->text . ' &rarr; List</h3>';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
        $data['orderby'] = (empty($orderby)) ? 'itemjahitan' : $orderby;
//        $data['loginas'] = $this->loginas;
        $data['pocoasc'] = ($orderby == 'selesai' || $orderby == 'date' || $orderby == 'jml') ? $asc : 'desc';
        $data['pocoorderby'] = (!empty($orderby) && $orderby != 'itemjahitan') ? $orderby : NULL;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->co_finish_only2($slug, $limit, $searchtext, $orderby, $asc);
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
            if (!$this->authorization->is_permitted(['delete_po'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $mainorder = $this->prtl_model->get_single_data($dataid);
        $s_oid = $this->session->userdata('oid');
        $oid = (empty($mainorder['oid'])) ? $s_oid : $mainorder['oid'];
        $data['success'] = '';
        if (!empty($mainorder['oid'])) {
            $result = $this->prtl_model->sql_rm_data($dataid);
            if ($result) {
                $data['success'] = "Tugas " . $mainorder['nama'] . " sukses dihapus";
                $this->activitylog->save_log($this->account, $this->ctlr, 'delete', $mainorder);
            } else {
                $data['success'] = "Tugas " . $mainorder['nama'] . " gagal dihapus";
            }
        }
        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
    }

    public function edit() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_po'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $orderid = $this->input->post("oid");
        $coid = $this->input->post("coid");
        $data['data2'] = $this->main_model->get_single_order($orderid);
        $data['data3'] = $this->cut_model->get_single_data($coid);

        $data['title'] = 'eprtl';
        $data['judul'] = '<h3>' . $this->text . ' &rarr; Edit</h3>';
        $data['hidden'] = array('dataid' => $dataid, 'coid' => $coid);

        $data['data'] = $this->prtl_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';
        $data['options'] = $this->get_options();

        $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
    }

    function arsip($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_po'])) {
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
        $rows = $this->main_model->co_finish_only2($slug, NULL, $searchtext, $orderby, $asc, '', $arsip);
        $this->pagination->base_url = site_url($this->ctlr . "/$funct/");
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;
        $this->pagination->uri_segment = 3;

        # set data for view
        $data['arsip'] = $arsip;
        $data['title'] = 'rprtl';
        $data['judul'] = '<h3>' . $this->text . ' &rarr; List</h3>';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
        $data['orderby'] = (empty($orderby)) ? 'itemjahitan' : $orderby;
//        $data['loginas'] = $this->loginas;
        $data['pocoasc'] = ($orderby == 'selesai' || $orderby == 'date' || $orderby == 'jml') ? $asc : 'desc';
        $data['pocoorderby'] = (!empty($orderby) && $orderby != 'itemjahitan') ? $orderby : NULL;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->co_finish_only2($slug, $limit, $searchtext, $orderby, $asc, '', $arsip);
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
