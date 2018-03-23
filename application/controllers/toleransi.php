<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Toleransi extends CI_Controller {

    private $ctlr = "toleransi";

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
        $this->load->model('toleransi_model');
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
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
            if (!$this->authorization->is_permitted(['update_toleransi', 'create_toleransi'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'a' . $this->ctlr;
        $data['success'] = '';
        $data['mode'] = 'insert';

        $this->form_validation->set_rules('hari', 'Toleransi target kirim (hari)', 'required|is_numeric|max_length[11]');

        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        $dataid = $this->input->post("dataid");

        if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
            if (!empty($dataid)) {
                $data['hidden'] = array('dataid' => $dataid);
                $data['mode'] = 'edit';
            }
            $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
        } else { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'hari' => set_value('hari'),
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_toleransi'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->toleransi_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Data sukses ditambah');
                    $form_data['id'] = $this->activitylog->get_id('cfg_toleransi');
                    $this->activitylog->save_log($data['account'], $this->ctlr, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', 'Data gagal ditambah');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_toleransi'])) {
                    redirect('home/blocked');
                }
                $this->toleransi_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', "Data berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->ctlr, 'update', $form_data);                
            }
            redirect($this->ctlr . '/');   // or whatever logic needs to occur
        }
    }

    function index() {
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/edit');
    }

    public function edit() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_toleransi'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
//        $dataid = $this->input->post("dataid");
        $dataid = 1;

        $data['title'] = 'e' . $this->ctlr;
        $data['hidden'] = array('dataid' => $dataid);

        $data['data'] = $this->toleransi_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';

        $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
    }

    public function json() {
        $rows = $this->toleransi_model->json_data();
        echo json_encode($rows);
    }

}
