<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bhnin extends CI_Controller {

    private $ctlr = "bhnin";

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
        $this->load->model(['ongkos_model', 'bhnin_model']);
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
            if (!$this->authorization->is_permitted(['update_bhnin', 'create_bhnin'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'a' . $this->ctlr;
        $data['success'] = '';
        $data['mode'] = 'insert';
        $data['colors'] = $this->get_options();
        $data['bahans'] = $this->bhn_options();

        $this->form_validation->set_rules('jenis', 'Jenis Bahan', 'required');
        $this->form_validation->set_rules('warnaid', 'Warna Bahan', 'required');
        $this->form_validation->set_rules('panjang', 'Panjang (Yard)', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('tgl', 'Tgl. Update', '');

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
                'warnaid' => set_value('warnaid'),
                'bahanid' => set_value('jenis'),
                'masuk' => set_value('panjang'),
                'tgl' => set_value('tgl')
            );

            $idata = $this->bbhn_model->get_single_data($form_data['bahanid']);
            $cdata = $this->color_model->get_single_data($form_data['warnaid']);

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_bhnin'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->bhnin_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', $idata['bahan'] . ' ' . $cdata['warna'] . ' sukses ditambah');
                    $form_data['id'] = $this->activitylog->get_id('cfg_stok_bahan');
                    $this->activitylog->save_log($data['account'], $this->ctlr, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', $idata['bahan'] . ' ' . $cdata['warna'] . ' gagal ditambah!');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_bhnin'])) {
                    redirect('home/blocked');
                }
                $this->bhnin_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', $idata['bahan'] . ' ' . $cdata['warna'] . " berhasil diupdate");
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
            if (!$this->authorization->is_permitted(['retrieve_bhnin'])) {
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
        $rows = $this->bhnin_model->list_data($slug, NULL, $searchtext, $orderby, $asc);
        $this->pagination->base_url = site_url($this->ctlr . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'l' . $this->ctlr;
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->bhnin_model->list_data($slug, $limit, $searchtext, $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/index', $data);
    }

    public function del($slug = NULL) {
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_bhnin'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $sqldata = $this->bhnin_model->get_single_data($dataid);
        $result = $this->bhnin_model->sql_rm_data($dataid);
        if ($result) {
            $data['success'] = "Data " . $sqldata['bahan'] . ' ' . $sqldata['color'] . " sukses dihapus";
            $this->activitylog->save_log($this->account, $this->ctlr, 'delete', $sqldata);
        } else {
            $data['success'] = "Data " . $sqldata['bahan'] . ' ' . $sqldata['color'] . " gagal dihapus";
        }

        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
    }

    public function edit() {
        maintain_ssl();
        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_bhnin'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");

        $data['title'] = 'e' . $this->ctlr;
        $data['hidden'] = array('dataid' => $dataid);

        $data['data'] = $this->bhnin_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';
        $data['colors'] = $this->get_options();
        $data['bahans'] = $this->bhn_options();

        $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
    }

    public function clients() {
        $rows = $this->bhnin_model->json_data();
        echo json_encode($rows);
    }

    private function get_options() {
        $this->load->model('color_model');
        $items = $this->color_model->list_data(FALSE, NULL, '', 'warna', 'asc');
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['warna'];
        }
        return $options;
    }

    private function bhn_options() {
        $this->load->model('bbhn_model');
        $items = $this->bbhn_model->list_data(FALSE, NULL, '', 'bahan', 'asc');
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['bahan'];
        }
        return $options;
    }

}
