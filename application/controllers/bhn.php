<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bhn extends CI_Controller {

    private $pref = "bhn";

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('url_helper');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('activitylog');
        $this->load->model(['main_model', 'bhn_model', 'bbhn_model', 'color_model']);
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->pref));
        }
    }

    function insert() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_'.$this->pref, 'create_'.$this->pref])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'abhn';
        $data['success'] = '';
        $data['mode'] = 'insert';

        $orderid = $this->input->post("oid");
        $dataid = $this->input->post("dataid");
        $data['data2'] = $this->main_model->get_single_order($orderid);
        $data['bahans'] = $this->bhn_options();
        $data['colors'] = $this->clr_options();

//            if(!empty($pekerjaid)){
        $this->form_validation->set_rules('jenis', 'Jenis Bahan', 'required');
        $this->form_validation->set_rules('warna', 'Warna', 'required');
        $this->form_validation->set_rules('panjang', 'Panjang', 'is_numeric|max_length[11]|required');
        $this->form_validation->set_rules('oid', 'Order ID', '');
//                $this->form_validation->set_message('max_length', 'Panjang max. {field} adalah {param} characters.');
//                $this->form_validation->set_message('required', '{field} {param} harus di isi.');
//                $this->form_validation->set_message('is_numeric', '{field} harus berupa angka.');
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
            $this->load->view($this->pref . '/insert', isset($data) ? $data : NULL);
        } else { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'oid' => set_value('oid'),
                'jenis' => set_value('jenis'),
                'warna' => set_value('warna'),
                'panjang' => set_value('panjang'),
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_'.$this->pref])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->bhn_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Penambahan bahan berhasil');
                    $form_data['id'] = $this->activitylog->get_id('cutting_bahan');
                    $this->activitylog->save_log($data['account'], $this->pref, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', 'Penambahan bahan gagal');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_'.$this->pref])) {
                    redirect('home/blocked');
                }
                $this->bhn_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', "Bahan berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->pref, 'update', $form_data);                
            }
//                        $this->session->set_flashdata('oid', $orderid);
            $this->session->set_userdata('oid', $orderid);
            redirect($this->pref . '/set');   // or whatever logic needs to occur                        
        }
    }

// view index.php dihapus
//    function index($slug = NULL){
//        maintain_ssl();
//
//        if ($this->authentication->is_signed_in())
//        {
//                $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
//        }
//        $this->load->helper('form');
//
//        # set session for search purpose 
//        $search_session = $this->session->userdata('searchtext');
//        $search_post = $this->input->post('searchtext');
//        $status_post = $this->input->post('status');
//        $searchtext = ($status_post) ? $search_post : $search_session;
//        $this->session->set_userdata('searchtext', $searchtext);
//
//        # pagination setup
//        $orderby = urldecode($this->uri->segment(3, 0));
//        $asc = urldecode($this->uri->segment(4, 0));
//        $asc = (empty($asc)) ? 'desc' : $asc;
//        $rows = $this->main_model->list_notfinish($slug, NULL, $searchtext, $orderby, $asc);
//        $config['base_url'] = site_url($this->pref . '/');
//        $config['total_rows'] = count($rows);
//        $ci = $this->pagination->initialize($config);
//
//        # set data for view
////        $limit = $ci->per_page;
////        $data['success'] = print_r($ci,1);
//        $limit = 100;
//        $data['title'] = 'lcut';
//        $data['slug'] = $slug;
//        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
//        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
//        $data['hidden'] = array('status' => 'TRUE');
//        $data['search_text'] = $searchtext;
////        $data['loginas'] = $this->loginas;
//
//        $data['pagination'] = $this->pagination->create_links();
//        $data['records'] = $this->main_model->list_notfinish($slug, $limit, $searchtext, $orderby, $asc);
//        $s_success = $this->session->flashdata('success');
//        $data['success'] = (!empty($s_success)) ? $s_success : '';
//        $data['no'] = !empty($slug) ? $slug + 1 : 1;
//        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";
//
//        //$this->load->view('header', $data);
//        $this->load->view($this->pref . '/index', $data);
//        //$this->load->view('footer');
//        
//    }

    public function del($slug = NULL) {
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_'.$this->pref])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $mainorder = $this->bhn_model->get_single_data($dataid);
        $s_oid = $this->session->userdata('oid');
        $oid = (empty($mainorder['oid'])) ? $s_oid : $mainorder['oid'];
        $data['success'] = '';
        if (!empty($mainorder['oid'])) {
            $result = $this->bhn_model->sql_rm_data($dataid);
            if ($result) {
                $data['success'] = "Bahan " . $mainorder['bahan'] . ' ' . $mainorder['color'] . " sukses dihapus";
                $this->activitylog->save_log($this->account, $this->pref, 'delete', $mainorder);
            } else {
                $data['success'] = "Bahan " . $mainorder['bahan'] . ' ' . $mainorder['color'] . " gagal dihapus";
            }
        }
        $this->session->set_flashdata('success', $data['success']);
        redirect($this->pref . '/set');   // or whatever logic needs to occur                        
    }

    public function set($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_'.$this->pref])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $s_oid = $this->session->userdata('oid');
        $orderid = (!empty($this->input->post("oid"))) ? $this->input->post("oid") : $s_oid;

        $data['title'] = 'sbhn';
        $data['hidden'] = array('orderid' => $orderid);

        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'desc' : $asc;

        # pagination setup
        $rows = $this->bhn_model->get_data_by_oid($orderid, $slug, NULL, '', $orderby, $asc);
        $this->pagination->base_url = site_url($this->pref . '/set/');
        $this->pagination->total_rows = count($rows);
        $this->pagination->uri_segment = 3;
        $limit = $this->pagination->per_page;

        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['data'] = $this->main_model->get_single_order($orderid);
        $data['attributes'] = array('role' => 'form', 'style' => 'margin:auto;');
        $data['records'] = $this->bhn_model->get_data_by_oid($orderid, $slug, $limit, '', $orderby, $asc);
        $data['mode'] = 'edit';
        $data['pagination'] = $this->pagination->create_links();
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";
        $this->session->set_userdata('oid', $orderid);

        $this->load->view($this->pref . '/bhnindex', isset($data) ? $data : NULL);
    }

    public function edit() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_'.$this->pref])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $orderid = $this->input->post("oid");
        $data['data2'] = $this->main_model->get_single_order($orderid);

        $data['title'] = 'ebhn';
        $data['hidden'] = array('dataid' => $dataid);

        $data['data'] = $this->bhn_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';

        $data['bahans'] = $this->bhn_options();
        $data['colors'] = $this->clr_options();

        $this->load->view($this->pref . '/insert', isset($data) ? $data : NULL);
    }

    private function bhn_options() {
        $items = $this->bbhn_model->list_data(FALSE, NULL, '', 'bahan', 'asc');
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['bahan'];
        }
        return $options;
    }

    private function clr_options() {
        $items = $this->color_model->list_data(FALSE, NULL, '', 'warna', 'asc');
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['warna'];
        }
        return $options;
    }

}
