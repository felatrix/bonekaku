 <?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    private $ctlr = "main";
    private $account = array();

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('url_helper');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->model('main_model');
        $this->load->library('activitylog');
        $this->load->model(['item_model', 'jenis_model', 'client_model']);
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model(array('account/account_model'));
        // Redirect unauthenticated users to signin page
        if (!$this->authentication->is_signed_in()) {
            redirect('account/sign_in/?continue=' . urlencode(base_url() . $this->ctlr));
        }
//        if (!$this->authorization->is_role("Admin")) {
//            redirect('home');         
//        }
    }

    function insert() {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            $this->account = $data['account'];
            if (!$this->authorization->is_permitted(['update_mainorder', 'create_mainorder'])) {
                redirect('home/blocked');
            }
        }
        $data['title'] = 'amo';
        $data['success'] = '';
        $data['mode'] = 'insert';
        $data['items'] = $this->get_options();
        $data['jenis'] = $this->get_jenis();
        $data['clients'] = $this->get_clients();

        $this->form_validation->set_rules('tgl_order', 'Tgl Order', 'required');
        $this->form_validation->set_rules('tgl_target', 'Tgl Target', '');
//		$this->form_validation->set_rules('search_nama', 'Nama', '');			
        $this->form_validation->set_rules('pemesanid', 'Nama', 'required');
//		$this->form_validation->set_rules('item_boneka', 'Item Boneka', 'max_length[20]');			
        $this->form_validation->set_rules('warna_boneka', 'Warna Boneka', '');
        $this->form_validation->set_rules('bahan_boneka', 'Bahan Boneka', '');
        $this->form_validation->set_rules('itemid', 'Jenis Boneka', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('aksesoris', 'Aksesoris', '');
        $this->form_validation->set_rules('jenis_tulisan', 'Jenis Tulisan', 'max_length[20]');
        $this->form_validation->set_rules('tulisan', 'Tulisan', 'max_length[100]');
        $this->form_validation->set_rules('warna_bordir', 'Warna Bordir', 'max_length[20]');
        $this->form_validation->set_rules('lain_lain', 'Lain - lain', '');
        $this->form_validation->set_rules('harga', 'Harga', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('tgl_dp', 'Tgl DP', '');
        $this->form_validation->set_rules('jml_dp', 'Jml DP', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('tgl_pelunasan', 'Tgl Pelunasan', '');
        $this->form_validation->set_rules('jml_pelunasan', 'Jml Pelunasan', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('ongkos_kirim', 'Ongkos Kirim', 'is_numeric|max_length[11]');
        $this->form_validation->set_rules('kirim_via', 'Kirim Via', 'max_length[20]');
        $this->form_validation->set_rules('tgl_kirim', 'Tgl Kirim', '');
        $this->form_validation->set_rules('cancel', 'Cancel', 'max_length[1]');
        $this->form_validation->set_rules('waitingconfirm', 'Tunggu Konfirmasi', 'max_length[1]');

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
        $orderid = $this->input->post("orderid");

        if ($this->form_validation->run() == FALSE) { // validation hasn't been passed
            if (!empty($orderid)) {
                $data['hidden'] = array('orderid' => $orderid);
                $data['mode'] = 'edit';
            }
            $this->load->view('main/main_order', isset($data) ? $data : NULL);
        } else { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'tglorder' => set_value('tgl_order'),
                'targetdate' => set_value('tgl_target'),
                'pemesanid' => set_value('pemesanid'),
//                            'itemboneka' => set_value('item_boneka'),
                'warnaboneka' => set_value('warna_boneka'),
                'bahanboneka' => set_value('bahan_boneka'),
                'itemid' => set_value('itemid'),
                'jumlah' => set_value('jumlah'),
                'aksesoris' => set_value('aksesoris'),
                'jenistulisan' => set_value('jenis_tulisan'),
                'tulisan' => set_value('tulisan'),
                'warnabordir' => set_value('warna_bordir'),
                'lainlain' => set_value('lain_lain'),
                'harga' => set_value('harga'),
                'tgldp' => set_value('tgl_dp'),
                'jmldp' => set_value('jml_dp'),
                'tglpelunasan' => set_value('tgl_pelunasan'),
                'jmlpelunasan' => set_value('jml_pelunasan'),
                'ongkoskirim' => set_value('ongkos_kirim'),
                'kirimvia' => set_value('kirim_via'),
                'tglkirim' => set_value('tgl_kirim'),
                'cancel' => set_value('cancel'),
                'waitingconfirm' => set_value('waitingconfirm')
            );

            if ($form_data['tglkirim'] != '0000-00-00' && $form_data['tglkirim'] != '' && $form_data['tglpelunasan'] != '0000-00-00' && $form_data['tglpelunasan'] != NULL && $form_data['jmlpelunasan'] != '0' && $form_data['jmlpelunasan'] != NULL) {
                $form_data['selesai'] = 1;
            } else {
                $form_data['selesai'] = 0;
            }
            if (empty($orderid)) {
                if (!$this->authorization->is_permitted(['create_mainorder'])) {
                    redirect('home/blocked');
                }
                // run insert model to write data to db
                if ($this->main_model->SaveForm($form_data) == TRUE) { // the information has therefore been successfully saved in the db
                    $this->session->set_flashdata('success', 'Penambahan order berhasil');
                    $form_data['id'] = $this->activitylog->get_id('main_order');
                    $this->activitylog->save_log($this->account, $this->ctlr, 'create', $form_data);
//				redirect('Main/success');   // or whatever logic needs to occur
                } else {
                    $this->session->set_flashdata('success', 'Penambahan order gagal');
//			echo 'An error occurred saving your information. Please try again later';
                    // Or whatever error handling is necessary
                }
            } else {
                if (!$this->authorization->is_permitted(['update_mainorder'])) {
                    redirect('home/blocked');
                }
                $this->main_model->update_order($orderid, $form_data);
                $form_data['id'] = $orderid;
                $this->activitylog->save_log($this->account, $this->ctlr, 'update', $form_data);
                $this->session->set_flashdata('success', $form_data['tglorder'] . " Order berhasil diupdate");
            }
            //$this->load->view('main/index', isset($data) ? $data : NULL);
            redirect('main/index');   // or whatever logic needs to occur
        }
    }

    function index($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            $this->account = $data['account'];
            if (!$this->authorization->is_permitted('retrieve_mainorder')) {
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
        $rows = $this->main_model->list_mainorder($slug, NULL, $searchtext, $orderby, $asc);
//        $config['base_url'] = site_url('main/');
//        $config['total_rows'] = count($rows);
        $this->pagination->base_url = site_url('main/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;

        # set data for view
        $data['title'] = 'lmo';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;

        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->list_mainorder($slug, $limit, $searchtext, $orderby, $asc);
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        //$this->load->view('header', $data);
        $this->load->view('main/index', $data);
        //$this->load->view('footer');
    }

    public function del($slug = NULL) {
        if ($this->authentication->is_signed_in()) {
            $this->account = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['delete_mainorder'])) {
                redirect('home/blocked');
            }
        }
        $this->load->helper('form');
        $orderid = $this->input->post("orderid");
        $mainorder = $this->main_model->get_single_order($orderid);
        $del = $this->main_model->rm_all_data_by_oid($orderid);
        $result = $this->main_model->sql_rm_order($orderid);
        if ($result && $del) {
            $this->activitylog->save_log($this->account, $this->ctlr, 'delete', $mainorder);
            $data['success'] = "Order " . $mainorder['itemjahitan'] . " (oid: " . $mainorder['id'] . ") sukses dihapus";
        } else {
            $data['success'] = "Order " . $mainorder['itemjahitan'] . " (oid: " . $mainorder['id'] . ") gagal dihapus";
        }

        /* # set session for search purpose 
          $search_session = $this->session->userdata('searchtext');
          $search_post = $this->input->post('searchtext');
          $status_post = $this->input->post('status');
          $searchtext = ($status_post) ? $search_post : $search_session;
          $this->session->set_userdata('searchtext', $searchtext);

          # pagination setup
          $orderby = urldecode($this->uri->segment(3, 0));
          $asc = urldecode($this->uri->segment(4, 0));
          $asc = (empty($asc)) ? 'desc' : $asc;
          $rows = $this->main_model->list_mainorder($slug, NULL, $searchtext, $orderby, $asc);
          $this->pagination->base_url = site_url('main/');
          $this->pagination->total_rows = count($rows);
          $limit = $this->pagination->per_page;

          # set data for view
          $data['title'] = 'dmo';
          $data['slug'] = $slug;
          $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
          $data['attributes'] = array('role' => 'form');
          $data['hidden'] = array('status' => 'TRUE');
          $data['search_text'] = $searchtext;
          //        $data['loginas'] = $this->loginas;

          $data['pagination'] = $this->pagination->create_links();
          $data['records'] = $this->main_model->list_mainorder($slug, $limit, $searchtext);
          $data['no'] = !empty($slug) ? $slug + 1 : 1;
          $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

          $this->load->view('main/index', $data); */
        $this->session->set_flashdata('success', $data['success']);
        redirect($this->ctlr . '/');   // or whatever logic needs to occur                        
    }

    public function edit() {
        $this->load->helper('form');
        $orderid = $this->input->post("orderid");

        $data['title'] = 'emo';
        $data['hidden'] = array('orderid' => $orderid);

        $data['mainorder'] = $this->main_model->get_single_order($orderid);
        $data['mode'] = 'edit';
        $data['success'] = '';
        $data['items'] = $this->get_options();
        $data['jenis'] = $this->get_jenis();
        $data['clients'] = $this->get_clients();

        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['update_mainorder'])) {
                redirect('home/blocked');
            }
        }
        $this->load->view('main/main_order', isset($data) ? $data : NULL);
    }

    private function get_options() {
        $items = $this->item_model->list_data(FALSE, NULL, '', 'itemjahitan', 'asc');
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['itemjahitan'];
        }
        return $options;
    }

    private function get_jenis() {
        $items = $this->jenis_model->list_data(FALSE, NULL, '', 'jenis', 'asc');
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['jenis']] = $val['jenis'];
        }
        return $options;
    }

    private function get_clients() {
        $items = $this->client_model->json_data();
        $options[''] = 'Silahkan pilih';
        foreach ($items as $val) {
            $options[$val['id']] = $val['name'];
        }
        return $options;
    }

    public function arsip($slug = NULL) {
        maintain_ssl();

        if ($this->authentication->is_signed_in()) {
            $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
            if (!$this->authorization->is_permitted(['retrieve_mainorder'])) {
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
        $orderby = urldecode($this->uri->segment(4, 0));
        $asc = urldecode($this->uri->segment(5, 0));
        $asc = (empty($asc)) ? 'desc' : $asc;
        $rows = $this->main_model->list_mainorder($slug, NULL, $searchtext, $orderby, $asc, 'arsip');
        $this->pagination->base_url = site_url('main/arsip/');
        $this->pagination->total_rows = count($rows);
        $limit = $this->pagination->per_page;
        $this->pagination->uri_segment = 3;

        # set data for view
        $data['title'] = 'rmo';
        $data['slug'] = $slug;
        $data['order'] = ($asc == 'asc') ? 'desc' : 'asc';
        $data['attributes'] = array('role' => 'form');
        $data['hidden'] = array('status' => 'TRUE');
        $data['search_text'] = $searchtext;
//        $data['loginas'] = $this->loginas;

        $data['arsip'] = 'arsip';
        $data['pagination'] = $this->pagination->create_links();
        $data['records'] = $this->main_model->list_mainorder($slug, $limit, $searchtext, $orderby, $asc, 'arsip');
        $s_success = $this->session->flashdata('success');
        $data['success'] = (!empty($s_success)) ? $s_success : '';
        $data['no'] = !empty($slug) ? $slug + 1 : 1;
        $data['result'] = (empty($data['records'])) ? "Data tidak ditemukan" : "";

        $this->load->view('main/index', $data);
    }

}
