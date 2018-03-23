<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tulisan extends CI_Controller {

    private $pref = "tulisan";

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
        $this->load->model('main_model');
        $this->load->model('tulisan_model');
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
            if (!$this->authorization->is_permitted(['update_tulisan', 'create_tulisan'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'atls';
        $data['success'] = '';
        $data['mode'] = 'insert';

        $orderid = $this->input->post("oid");
        $dataid = $this->input->post("dataid");
        $data['data2'] = $this->main_model->get_single_order($orderid);

//            if(!empty($pekerjaid)){
        $this->form_validation->set_rules('kegiatan', 'Kegiatan', 'max_length[50]|required');
        $this->form_validation->set_rules('antar', 'Tanggal Antar', 'required');
        $this->form_validation->set_rules('kembali', 'Tanggal Kembali', '');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'is_numeric');
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
            $this->load->view($this->pref . '/insert', isset($data) ? $data : NULL);
        } else { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'oid' => set_value('oid'),
                'kegiatan' => set_value('kegiatan'),
                'antar' => set_value('antar'),
                'kembali' => set_value('kembali'),
                'jumlah' => set_value('jumlah'),
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_tulisan'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->tulisan_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Penambahan kegiatan berhasil');
                    $form_data['id'] = $this->activitylog->get_id('cutting_sub');
                    $this->activitylog->save_log($data['account'], $this->pref, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', 'Penambahan kegiatan gagal');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_tulisan'])) {
                    redirect('home/blocked');
                }
                $this->tulisan_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', "Kegiatan berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->pref, 'update', $form_data);                
            }
//                        $this->session->set_flashdata('oid', $orderid);
            $this->session->set_userdata('oid', $orderid);
            redirect($this->pref . '/set');   // or whatever logic needs to occur                        
        }
    }

    public function del($slug = NULL) {
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_tulisan'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $mainorder = $this->tulisan_model->get_single_data($dataid);
        $s_oid = $this->session->userdata('oid');
        $oid = (empty($mainorder['oid'])) ? $s_oid : $mainorder['oid'];
        $data['success'] = '';
        if (!empty($mainorder['oid'])) {
            $result = $this->tulisan_model->sql_rm_data($dataid);
            if ($result) {
                $data['success'] = "Kegiatan " . $mainorder['kegiatan'] . " sukses dihapus";
                $this->activitylog->save_log($this->account, $this->pref, 'delete', $mainorder);
            } else {
                $data['success'] = "Kegiatan " . $mainorder['kegiatan'] . " gagal dihapus";
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

        $data['title'] = 'stls';
        $data['hidden'] = array('orderid' => $orderid);

        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $this->pagination->suffix = (!empty($orderby) && !empty($asc)) ? '/' . $orderby . '/' . $asc : '';
        $asc = (empty($asc)) ? 'desc' : $asc;

        $rows = $this->tulisan_model->get_data_by_oid($orderid, $slug, NULL, '', $orderby, $asc);
        $this->pagination->base_url = site_url($this->pref . '/set/');
        $this->pagination->total_rows = count($rows);
        $this->pagination->uri_segment = 3;
        $limit = $this->pagination->per_page;

        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['data'] = $this->main_model->get_single_order($orderid);
        $data['attributes'] = array('role' => 'form', 'style' => 'margin:auto;');
        $data['records'] = $this->tulisan_model->get_data_by_oid($orderid, $slug, $limit, '', $orderby, $asc);
        $data['mode'] = 'edit';
        $data['pagination'] = $this->pagination->create_links();
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";
        $this->session->set_userdata('oid', $orderid);

        $this->load->view($this->pref . '/index', isset($data) ? $data : NULL);
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

        $data['title'] = 'etls';
        $data['hidden'] = array('dataid' => $dataid);

        $data['data'] = $this->tulisan_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';

        $this->load->view($this->pref . '/insert', isset($data) ? $data : NULL);
    }

}
