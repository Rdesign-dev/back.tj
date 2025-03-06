<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Brand_model', 'brand');
    }

    public function index() {
        $data['title'] = "Brand Detail";
        $data['brands'] = $this->brand->find_all();
        $this->template->load('templates/dashboard', 'brand/index', $data);
    }

    // Tambahkan method lain seperti add, edit, delete sesuai kebutuhan
}