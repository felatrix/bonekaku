<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajaxset extends CI_Controller {

    private $ctlr = "ajaxset";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('unread');
        // Load the necessary stuff...
        $this->load->helper(array('language', 'form', 'url', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
    }

    public function index() {
        $oid = $this->input->post("oid");
        $coid = $this->input->post("coid");
        $ctlr = $this->input->post("ctlr");
        if($ctlr == 'main' && !empty($oid)){
            echo $this->unread->set_read($ctlr, $oid);
        } elseif(!empty($ctlr) && !empty($coid) && !empty($oid)){
            echo $this->unread->set_read($ctlr, $oid, $coid);
        } else {
            echo FALSE;            
        }
    }

    public function clients() {
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
        $rows = $this->client_model->json_data();
        echo json_encode($rows);
    }

}
