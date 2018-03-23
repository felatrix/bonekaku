<?php

class Home extends CI_Controller {

    private $ctlr = "home";
    
    function __construct() {
        parent::__construct();

        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
    }

    function index() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
//            if ($this->authorization->is_role("Admin")) {
            if ($this->authorization->is_permitted('retrieve_mainorder')) {
                redirect('main/');
//            } elseif ($this->authorization->is_role("Cutting")) {
            } elseif ($this->authorization->is_permitted('retrieve_co')) {
                redirect('cut/');
//            } elseif ($this->authorization->is_role("Pretel")) {
            } elseif ($this->authorization->is_permitted('retrieve_po')) {
                redirect('prtl/');
//            } elseif ($this->authorization->is_role("Sewing")) {
            } elseif ($this->authorization->is_permitted('retrieve_so')) {
                redirect('sew/');
//            } elseif ($this->authorization->is_role("Finishing")) {
            } elseif ($this->authorization->is_permitted('retrieve_fo')) {
                redirect('fin/');
            } else {
                redirect('home/blocked');                
            }
        } else {
            redirect('account/sign_in/?continue='.urlencode(base_url()));            
        }

//        $data['title'] = 'test';
//        $this->load->view('home', isset($data) ? $data : NULL);
    }
    
    function blocked(){
        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
        } else {
            redirect('account/sign_in/?continue='.urlencode(base_url()));            
        }
        $data['title'] = 'l' . $this->ctlr;
        $this->load->view('home', isset($data) ? $data : NULL);        
    }

}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */