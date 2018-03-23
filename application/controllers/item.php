<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Item extends CI_Controller {

    private $ctlr = "item";

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
        $this->load->model('item_model');
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        // Redirect unauthenticated users to signin page
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
    }

    function insert() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_jenis', 'create_jenis'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'aitem';
        $data['success'] = '';
        $data['mode'] = 'insert';

        $this->form_validation->set_rules('itemjahitan', 'Item Jahitan', 'required');
        $this->form_validation->set_rules('minperform', 'Min. Performance', 'is_numeric|max_length[11]|required');
        $this->form_validation->set_rules('bonus', 'Bonus', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('penalty', 'Penalty', 'is_numeric|max_length[11]');

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
                'itemjahitan' => set_value('itemjahitan'),
                'minperform' => set_value('minperform'),
                'bonus' => set_value('bonus'),
                'penalty' => set_value('penalty')
            );

            if (empty($dataid)) {
                if (!$this->authorization->is_permitted(['create_jenis'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->item_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', $form_data['itemjahitan'] . ' sukses ditambah');
                    $form_data['id'] = $this->activitylog->get_id('cfg_boneka_performance');
                    $this->activitylog->save_log($data['account'], $this->ctlr, 'create', $form_data);
                } else {
                    $this->session->set_flashdata('success', $form_data['itemjahitan'] . ' gagal ditambah');
                }
            } else {
                if (!$this->authorization->is_permitted(['update_jenis'])) {
                    redirect('home/blocked');
                }
                $this->item_model->update_data($dataid, $form_data);
                $this->session->set_flashdata('success', $form_data['itemjahitan'] . " berhasil diupdate");
                $form_data['id'] = $dataid;
                $this->activitylog->save_log($data['account'], $this->ctlr, 'update', $form_data);                
            }
            redirect($this->ctlr . '/index');   // or whatever logic needs to occur
        }
    }

//	function success()
//	{
//			echo 'this form has been successfully submitted with all validation being passed. All messages or logic here. Please note
//			sessions have not been used and would need to be added in to suit your app';
//	}

    function index($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_jenis'])) {
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
        $asc = (empty($asc)) ? 'asc' : $asc;
        $rows = $this->item_model->list_data($slug, NULL, $searchtext, $orderby, $asc);
        $this->pagination->base_url = site_url($this->ctlr . '/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'litem';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form', 'style' => 'margin: auto');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->item_model->list_data($slug, $limit, $searchtext, $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        //$this->load->view('header', $data);
        $this->load->view($this->ctlr . '/index', $data);
        //$this->load->view('footer');
    }

    public function del($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_jenis'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");
        $sqldata = $this->item_model->get_single_data($dataid);
        $result = $this->item_model->sql_rm_data($dataid);
        if ($result) {
            $data['success'] = "Data " . $sqldata['itemjahitan'] . " sukses dihapus";
            $this->activitylog->save_log($this->account, $this->ctlr, 'delete', $sqldata);
        } else {
            $data['success'] = "Data " . $sqldata['itemjahitan'] . " gagal dihapus karena ada relasi dengan tabel lain.";
        }

        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
    }

    public function edit() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_jenis'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $dataid = $this->input->post("dataid");

        $data['title'] = 'eitem';
        $data['hidden'] = array('dataid' => $dataid);

        $data['data'] = $this->item_model->get_single_data($dataid);
        $data['mode'] = 'edit';
        $data['success'] = '';

        $this->load->view($this->ctlr . '/insert', isset($data) ? $data : NULL);
    }

    public function json() {
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
        $rows = $this->item_model->json_data();
        echo json_encode($rows);
    }

}
