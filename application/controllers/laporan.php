<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan extends CI_Controller {

    private $ctlr = "laporan";
    private $account = "";

    function __construct() {
        parent::__construct();
        $this->load->library(['form_validation', 'pagination', 'session']);
        $this->load->database();
        $this->load->helper(['form', 'url', 'url_helper', 'csv']);
        $this->load->model(['main_model', 'cut_model', 'fin_model', 'sew_model', 'item_model', 'jenis_model', 'pekerja_model','toleransi_model']);
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
        } else {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));            
        }
    }

    function index($slug = NULL) {
        $laporanAll = ['laporan_percust', 'laporan_top10', 'laporan_peritem', 'laporan_itemdate'
        , 'laporan_blmlunas', 'laporan_konsumsi', 'laporan_jenis', 'laporan_sewperform'
        , 'laporan_biayasew', 'laporan_biayapretel', 'laporan_biayafin', 'laporan_stok','laporan_crcsv'];
        maintain_ssl();
        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted($laporanAll)) {
                redirect('home/blocked');
            }
        }

        # set data for view
        $start_d = date('Y-m-d', strtotime("-31 days"));
        $stop_d = date('Y-m-d');
        $start_sess = $this->session->userdata('starth');
        $stop_sess = $this->session->userdata('endh');
        $staff_sess = $this->session->userdata('idstaff');
        $jenis_sess = $this->session->userdata('jenis');

        $data['start'] = (empty($start_sess)) ? $start_d : $start_sess;
        $data['stop'] = (empty($stop_sess)) ? $stop_d : $stop_sess;
        $data['idstaff'] = (empty($staff_sess)) ? '-' : $staff_sess;
        $data['jenisdef'] = (empty($jenis_sess)) ? '-' : $jenis_sess;

        $data['title'] = 'l' . $this->ctlr;
        $data['jenis'] = $this->get_jenis();
        $data['pekerjas'] = $this->get_pekerja();
        
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $this->load->view($this->ctlr . '/index', $data);
    }

    function peritem($slug = NULL) {
        maintain_ssl();
        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['laporan_peritem'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');

        # set session for search purpose 
        $start_post = $this->input->post('starth');
        $stop_post = $this->input->post('endh');
        $start_session = $this->session->userdata('starth');
        $stop_session = $this->session->userdata('endh');
        $start_tgl = (!empty($start_post)) ? $start_post : $start_session;
        $stop_tgl = (!empty($stop_post)) ? $stop_post : $stop_session;
        $this->session->set_userdata('starth', $start_tgl);
        $this->session->set_userdata('endh', $stop_tgl);
        # set session for search purpose 
        $search_session = $this->session->userdata('searchtext');
        $search_post = $this->input->post('bln');
        $searchtext = (!empty($search_post)) ? $search_post : $search_session;
        $this->session->set_userdata('searchtext', $searchtext);

        # pagination setup
        $funct = urldecode($this->uri->segment(2, 0));
        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'asc' : $asc;
//        $rows = $this->main_model->item_perbln($slug, NULL, $searchtext, $orderby, $asc);
        $rows = $this->main_model->item_perbln($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;
        $this->pagination->uri_segment = 3;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
//        $data['loginas'] = $this->loginas;

        $data['pagination'] = $this->pagination->create_links();
//        $data['records'] = $this->main_model->item_perbln($slug, $limit, $searchtext, $orderby, $asc);
        $data['records'] = $this->main_model->item_perbln($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/peritem', $data);
    }

    function percust($slug = NULL) {
        maintain_ssl();
        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['laporan_percust'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');

        # set session for search purpose 
        $start_post = $this->input->post('starth');
        $stop_post = $this->input->post('endh');
        $start_session = $this->session->userdata('starth');
        $stop_session = $this->session->userdata('endh');
        $start_tgl = (!empty($start_post)) ? $start_post : $start_session;
        $stop_tgl = (!empty($stop_post)) ? $stop_post : $stop_session;
        $this->session->set_userdata('starth', $start_tgl);
        $this->session->set_userdata('endh', $stop_tgl);
        # set session for search purpose 
        $search_session = $this->session->userdata('searchtext');
        $search_post = $this->input->post('bln');
        $searchtext = (!empty($search_post)) ? $search_post : $search_session;
        $this->session->set_userdata('searchtext', $searchtext);

        # pagination setup
        $funct = urldecode($this->uri->segment(2, 0));
        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'desc' : $asc;
        $rows = $this->main_model->item_percust($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
//        $this->pagination->per_page = 50;
        $limit = $this->pagination->per_page;
        $this->pagination->uri_segment = 3;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->item_percust($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/percust', $data);
    }

    function topten($slug = NULL) {
        maintain_ssl();
        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['laporan_top10'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');

        # set session for search purpose 
        $start_post = $this->input->post('starth');
        $stop_post = $this->input->post('endh');
        $start_session = $this->session->userdata('starth');
        $stop_session = $this->session->userdata('endh');
        $start_tgl = (!empty($start_post)) ? $start_post : $start_session;
        $stop_tgl = (!empty($stop_post)) ? $stop_post : $stop_session;
        $this->session->set_userdata('starth', $start_tgl);
        $this->session->set_userdata('endh', $stop_tgl);
        # set session for search purpose 
        $search_session = $this->session->userdata('searchtext');
        $search_post = $this->input->post('bln');
        $searchtext = (!empty($search_post)) ? $search_post : $search_session;
        $this->session->set_userdata('searchtext', $searchtext);

        # pagination setup
//        $orderby = urldecode($this->uri->segment(4, 0));
//        $asc = urldecode($this->uri->segment(5, 0));
//        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/'.$orderby.'/'.$asc : '';
//        $asc = (empty($asc)) ? 'asc' : $asc;
        $limit = 10;
        $rows = $this->main_model->top_ten($slug, $limit, '', NULL, 'desc', $start_tgl, $stop_tgl);
        $this->pagination->base_url = site_url($this->ctlr . '/');
        $this->pagination->total_rows = count($rows);

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
//        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->top_ten($slug, $limit, '', NULL, 'desc', $start_tgl, $stop_tgl);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/topten', $data);
    }

    function bydate($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['laporan_itemdate'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');

        # set session for search purpose 
        $start_post = $this->input->post('starth');
        $stop_post = $this->input->post('endh');
        $field = $this->input->post('field');
        $start_session = $this->session->userdata('starth');
        $stop_session = $this->session->userdata('endh');
        $start_tgl = (!empty($start_post)) ? $start_post : $start_session;
        $stop_tgl = (!empty($stop_post)) ? $stop_post : $stop_session;
        $this->session->set_userdata('starth', $start_tgl);
        $this->session->set_userdata('endh', $stop_tgl);
        # set session for search purpose 
//        $search_session = $this->session->userdata('searchtext');
//        $search_post = $this->input->post('bln');
//        $searchtext = (!empty($search_post)) ? $search_post : $search_session;
//        $this->session->set_userdata('searchtext', $searchtext);
        # pagination setup
        $funct = urldecode($this->uri->segment(2, 0));
        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'desc' : $asc;
        $rows = $this->main_model->item_bydate($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $field);
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;
        $this->pagination->uri_segment = 3;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
//        $data['search_text'] = $searchtext;
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
//        $data['loginas'] = $this->loginas;
        switch ($field) {
            case 'estdate': $txt_field = 'Target Kirim';
                break;
            case 'tglkirim': $txt_field = 'Tgl. Kirim Aktual';
                break;
            default : $txt_field = 'Tgl. Order';
                break;
        }
        $data['field'] = $txt_field;
        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->item_bydate($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, $field);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";
        $data['toleransi'] = $this->toleransi_model->get_single_data('1');            

        $this->load->view($this->ctlr . '/bydate', $data);
    }

    private function inisialisasi($dasc = '') {
//        maintain_ssl();
//
//        if ($this->authentication->is_signed_in()){
//            $account = $this->account_model->get_by_id($this->session->userdata('account_id'));
//        }
        $this->load->helper('form');

        # set session for search purpose 
        $start_post = $this->input->post('starth');
        $stop_post = $this->input->post('endh');
        $start_session = $this->session->userdata('starth');
        $stop_session = $this->session->userdata('endh');
        $start_tgl = (!empty($start_post)) ? $start_post : $start_session;
        $stop_tgl = (!empty($stop_post)) ? $stop_post : $stop_session;
        $this->session->set_userdata('starth', $start_tgl);
        $this->session->set_userdata('endh', $stop_tgl);

        # set session for search purpose 
        $search_session = $this->session->userdata('searchtext');
        $search_post = $this->input->post('bln');
        $searchtext = (!empty($search_post)) ? $search_post : $search_session;
        $this->session->set_userdata('searchtext', $searchtext);

        # pagination setup
        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->uri_segment = 3;
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? (!empty($dasc) ? 'asc' : 'desc') : $asc;
        $defvar = ['asc' => $asc, 'orderby' => $orderby, 'searchtext' => $searchtext,
            'start_tgl' => $start_tgl, 'stop_tgl' => $stop_tgl];
        return $defvar;
    }

    function pertulisan($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_jenis'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi();
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];
        $jenis_post = $this->input->post('jenis_tulisan');
        $jenis_session = $this->session->userdata('jenis');
        $jenistulisan = (!empty($jenis_post)) ? $jenis_post : $jenis_session;
        $this->session->set_userdata('jenis', $jenistulisan);

        $rows = $this->main_model->cut_pertulisan($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $jenistulisan);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['jenis'] = $jenistulisan;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
//        $data['search_text'] = $searchtext;
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->cut_pertulisan($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, $jenistulisan);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/pertulisan', $data);
    }

    private function get_jenis() {
        $items = $this->jenis_model->list_data(FALSE, NULL, '', 'jenis', 'asc');
        $options['-'] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['jenis']] = $val['jenis'];
        }
        return $options;
    }

    private function get_pekerja() {
        $items = $this->pekerja_model->list_data(FALSE, NULL, '', 'nama', 'asc');
        $options['-'] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['nama'] . '-' . $val['jenisactivity'];
        }
        return $options;
    }

    function blmlunas($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_blmlunas'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];

        $rows = $this->main_model->blm_lunas($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
//        $data['search_text'] = $searchtext;
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->blm_lunas($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/blmlunas', $data);
    }

    function sewnilai($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_sewperform'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];
        $staff_post = $this->input->post('pekerja');
        $staff_session = $this->session->userdata('idstaff');
        $staffval = (!empty($staff_post)) ? $staff_post : $staff_session;
        $this->session->set_userdata('idstaff', $staffval);

        $rows = $this->main_model->sew_nilai($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['rows'] = count($rows);
        $data['total'] = $this->main_model->sew_nilai($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval, 'sum');
        
        $get_nama = $this->pekerja_model->get_single_data($staffval);
        $data['nama'] = (!empty($get_nama)) ? $get_nama['nama'] : '-';
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->sew_nilai($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function sewongkos($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_biayasew'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];
        $staff_post = $this->input->post('pekerja');
        $staff_session = $this->session->userdata('idstaff');
        $staffval = (!empty($staff_post)) ? $staff_post : $staff_session;
        $this->session->set_userdata('idstaff', $staffval);

        $rows = $this->main_model->sew_nilai($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['rows'] = count($rows);
        $get_total = $this->main_model->sew_nilai($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval, '', 'total');
        
        $data['total'] = (!empty($get_total)) ? $get_total[0]['total'] : '';
        $get_nama = $this->pekerja_model->get_single_data($staffval);
        $data['nama'] = (!empty($get_nama)) ? $get_nama['nama'] : '-';
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->sew_nilai($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function prtlongkos($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_biayapretel'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];
        $staff_post = $this->input->post('pekerja');
        $staff_session = $this->session->userdata('idstaff');
        $staffval = (!empty($staff_post)) ? $staff_post : $staff_session;
        $this->session->set_userdata('idstaff', $staffval);

        $rows = $this->main_model->prtl_ongkos($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['rows'] = count($rows);
        $get_total = $this->main_model->prtl_ongkos($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval, 'total');
        
        $data['total'] = (!empty($get_total)) ? $get_total[0]['total'] : '';
        $get_nama = $this->pekerja_model->get_single_data($staffval);
        $data['nama'] = (!empty($get_nama)) ? $get_nama['nama'] : '-';
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->prtl_ongkos($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function finongkos($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_biayafin'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];
        $staff_post = $this->input->post('pekerja');
        $staff_session = $this->session->userdata('idstaff');
        $staffval = (!empty($staff_post)) ? $staff_post : $staff_session;
        $this->session->set_userdata('idstaff', $staffval);

        $rows = $this->main_model->fin_ongkos($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['rows'] = count($rows);
        $get_total = $this->main_model->fin_ongkos($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval, 'total');
        
        $data['total'] = (!empty($get_total)) ? $get_total[0]['total'] : '';
        $get_nama = $this->pekerja_model->get_single_data($staffval);
        $data['nama'] = (!empty($get_nama)) ? $get_nama['nama'] : '-';
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->fin_ongkos($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, $staffval);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function avgbhn($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_konsumsi'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];

        $rows = $this->main_model->avg_bhn($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->avg_bhn($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function stokbhn($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_stok'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];

        $rows = $this->main_model->stok_bhn($slug, NULL, '', $orderby, $asc);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->stok_bhn($slug, $limit, '', $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function inweek($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_inweek'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = date('Y-m-d');
        $stop_tgl = date('Y-m-d', strtotime("+7 days"));

        $rows = $this->main_model->item_inweek($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl, 'estdate');
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->item_inweek($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl, 'estdate');
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function cmt($slug = NULL) {
        if (!$this->authorization->is_permitted(['laporan_cmt'])) {
            redirect('home/blocked');
        }
        $ini = $this->inisialisasi('asc');
        $orderby = $ini['orderby'];
        $asc = $ini['asc'];
        $start_tgl = $ini['start_tgl'];
        $stop_tgl = $ini['stop_tgl'];
        $searchtext = $ini['searchtext'];

        $rows = $this->main_model->cmt_progress($slug, NULL, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $funct = urldecode($this->uri->segment(2, 0));
        $this->pagination->base_url = site_url($this->ctlr . '/' . $funct . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['start'] = $start_tgl;
        $data['stop'] = $stop_tgl;
        $data['account'] = $this->account;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->cmt_progress($slug, $limit, '', $orderby, $asc, $start_tgl, $stop_tgl);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/' . $funct, $data);
    }

    function crcsv(){
            $this->load->helper('url');
            $this->load->helper('csv');

            $query = $this->db->query('SELECT * FROM table');
            $num = $query->num_fields();
            $var =array();
            $i=1;
            $fname="";
            while($i <= $num){
                $test = $i;
                $value = $this->input->post($test);

                if($value != ''){
                        $fname= $fname." ".$value;
                        array_push($var, $value);

                    }
                 $i++;
            }

            $fname = trim($fname);

            $fname=str_replace(' ', ',', $fname);

            $this->db->select($fname);
            $quer = $this->db->get('table');

            query_to_csv($quer,TRUE,'Products_'.date('dMy').'.csv');

        }
}


   
  