<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cut extends CI_Controller {

    private $ctlr = "cut";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['pagination', 'session', 'form_validation', 'activitylog']);
        $this->load->model(['tulisan_model', 'pekerja_model', 'main_model', 'cut_model', 'bhn_model']);
        $this->load->library('unread');
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl', 'url_helper'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
//            if (!$this->authorization->is_role("Admin") && !$this->authorization->is_role("Cutting")) {
//                redirect('home');         
//            }
    }

    function insert() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_co', 'create_co'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'acut';
        $data['success'] = '';
        $data['mode'] = 'insert';

        $orderid = $this->input->post("oid");
        $dataid = $this->input->post("dataid");
//            $pekerjaid = $this->input->post("pekerjaid");
        $data['data2'] = $this->main_model->get_single_order($orderid);
        $data['options'] = $this->get_options();

//            if(!empty($pekerjaid)){
//		$this->form_validation->set_rules('search_nama', 'Pekerja', 'required');			
        $this->form_validation->set_rules('pekerjaid', 'Pekerja', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'is_numeric|max_length[11]|required');
        $this->form_validation->set_rules('tgl', 'Tgl. Selesai', '');
        $this->form_validation->set_rules('oid', 'Order ID', '');

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
//            }                
        if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
            if (!empty($dataid)) {
                $data['hidden'] = array('dataid' => $dataid);
                $data['mode'] = 'edit';
            }
            if (!empty($orderid)) {
                $data['hidden'] = ['oid' => $orderid];
            }
            $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
        } else { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'oid' => set_value('oid'),
                'date' => set_value('tgl'),
                'bywho' => set_value('pekerjaid'),
                'jml' => set_value('jumlah'),
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_co'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->cut_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Penambahan tugas berhasil');
                    $form_data['id'] = $this->activitylog->get_id('cutting_order');
                    $this->activitylog->save_log($data['account'], $this->ctlr, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', 'Penambahan tugas gagal');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_co'])) {
                    redirect('home/blocked');
                }
                $this->cut_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', "Tugas berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->ctlr, 'update', $form_data);                
            }
            //$this->load->view($this->ctlr . '/index', isset($data) ? $data : NULL);
//                        $this->session->set_flashdata('oid', $orderid);
            $this->session->set_userdata('oid', $orderid);
//                        redirect($this->ctlr . '/set');   // or whatever logic needs to occur
            redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
        }
    }

    function index($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_co'])) {
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
        $rows = $this->main_model->list_notfinish($slug, NULL, $searchtext, $orderby, $asc);
        $this->pagination->base_url = site_url($this->ctlr . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;
//        $config['base_url'] = site_url($this->ctlr . '/');
//        $config['total_rows'] = count($rows);
//        $ci = $this->pagination->initialize($config);
        # set data for view
        $data['title'] = 'lcut';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->list_notfinish($slug, $limit, $searchtext, $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/index', $data);
    }

    public function del($slug = NULL) {
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_co'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $mainorder = $this->cut_model->get_single_data($dataid);
        $s_oid = $this->session->userdata('oid');
        $oid = (empty($mainorder['oid'])) ? $s_oid : $mainorder['oid'];
        $data['success'] = '';
//        if (!empty($mainorder['oid'])) {
        if (!empty($oid)) {
            $result = $this->cut_model->sql_rm_data($dataid);
            $delChild = $this->main_model->rm_all_data_by_coid($oid,$dataid);
            if ($result && $delChild) {
                $data['success'] = "Tugas " . $mainorder['nama'] . " sukses dihapus";
                $this->activitylog->save_log($this->account, $this->ctlr, 'delete', $mainorder);
            } else {
                $data['success'] = "Tugas " . $mainorder['nama'] . " gagal dihapus";
            }
        }
        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
    }

    /* Untuk archieve
      public function set($slug = NULL)
      {
      $this->load->helper('form');
      $s_oid = $this->session->userdata('oid');
      $orderid = (!empty($this->input->post("orderid"))) ? $this->input->post("orderid") : $s_oid;

      $data['title'] = 'sco';
      $data['hidden'] = array('orderid' => $orderid);

      $orderby = urldecode($this->uri->segment(3, 0));
      $asc = urldecode($this->uri->segment(4, 0));
      $asc = (empty($asc)) ? 'desc' : $asc;

      $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
      $data['data'] = $this->main_model->get_single_order($orderid);
      $data['attributes'] = array('role' => 'form', 'style' => 'margin:auto;');
      $data['records'] = $this->cut_model->get_data_by_oid($orderid);
      $data['mode'] = 'edit';
      $data['pagination'] = $this->pagination->create_links();
      $s_success = $this->session->flashdata('success');
      $data['success'] = (!empty($s_success)) ? $s_success : '';
      $data['no'] = !empty($slug) ? $slug + 1 : 1;
      $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

      $this->load->view($this->ctlr . '/cutindex', isset($data) ? $data : NULL);
      }
     */

    public function edit() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_co'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $orderid = $this->input->post("oid");
        $data['data2'] = $this->main_model->get_single_order($orderid);

        $data['title'] = 'ecut';
        $data['hidden'] = array('dataid' => $dataid);

        $data['data'] = $this->cut_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';
        $data['options'] = $this->get_options();

        $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
    }

    function arsip($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_co'])) {
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
        $rows = $this->main_model->list_notfinish($slug, NULL, $searchtext, $orderby, $asc, $arsip);
        $this->pagination->base_url = site_url($this->ctlr . "/$funct/");
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;
        $this->pagination->uri_segment = 3;

        # set data for view
        $data['title'] = 'rcut';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;

        $data['arsip'] = $arsip;
        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->list_notfinish($slug, $limit, $searchtext, $orderby, $asc, $arsip);
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
