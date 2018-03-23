<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Control extends CI_Controller {

    private $ctlr = "control";

    function __construct() {
        parent::__construct();
        $this->load->library(['form_validation', 'pagination', 'session']);
        $this->load->database();
        $this->load->helper(['form', 'url', 'url_helper']);
        $this->load->model(['prtl_model', 'main_model', 'cut_model', 'fin_model', 'sew_model', 'item_model', 'toleransi_model']);
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
        if (!$this->authorization->is_permitted(['lihat_control'])) {
            redirect('home/blocked');
        }
    }

    function index($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
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
        $asc = (empty($asc)) ? 'asc' : $asc;
        $rows = $this->main_model->control_list($slug, NULL, $searchtext, $orderby, $asc);
        $this->pagination->base_url = site_url($this->ctlr . '/');
        $this->pagination->total_rows = count($rows);
//        $this->pagination->per_page = 5;
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
        $data['records'] = $this->main_model->control_list($slug, $limit, $searchtext, $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        //$this->load->view('header', $data);
        $this->load->view($this->ctlr . '/index', $data);
        //$this->load->view('footer');
    }

    function arsip($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
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
        $asc = (empty($asc)) ? 'asc' : $asc;
        $arsip = 'arsip';
        $rows = $this->main_model->control_list($slug, NULL, $searchtext, $orderby, $asc, $arsip);
        $this->pagination->base_url = site_url($this->ctlr . "/$funct/");
        $this->pagination->total_rows = count($rows);
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

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->control_list($slug, $limit, $searchtext, $orderby, $asc, $arsip);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view($this->ctlr . '/index', $data);
    }

}
