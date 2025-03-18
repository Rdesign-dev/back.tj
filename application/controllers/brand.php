<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Brand_model', 'brand');
    }

    public function index() 
    {
        // Update promo status first
        date_default_timezone_set('Asia/Jakarta');
        $this->brand->update_promo_status();
        
        $data['title'] = "Brand Detail";
        $data['brands'] = $this->brand->find_all();
        // Add vouchers data
        $data['vouchers'] = $this->brand->get_all_vouchers();
        $this->template->load('templates/dashboard', 'brand/index', $data);
    }

    public function get_brand_promos($brand_id) {
        try {
            if (!$this->input->is_ajax_request()) {
                show_404();
                return;
            }

            $brand_id = intval($brand_id);
            if ($brand_id <= 0) {
                throw new Exception('Invalid brand ID');
            }

            $promos = $this->brand->get_brand_promos($brand_id);
            
            // Format dates for JSON response
            foreach ($promos as &$promo) {
                $promo['available_from'] = $promo['available_from'] ? date('Y-m-d H:i:s', strtotime($promo['available_from'])) : null;
                $promo['valid_until'] = $promo['valid_until'] ? date('Y-m-d H:i:s', strtotime($promo['valid_until'])) : null;
            }

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($promos));
                
        } catch (Exception $e) {
            log_message('error', 'Brand promos error: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function get_brand_details($brand_id) {
        try {
            if (!$this->input->is_ajax_request()) {
                show_404();
                return;
            }

            $brand_id = intval($brand_id);
            if ($brand_id <= 0) {
                throw new Exception('Invalid brand ID');
            }

            $brand = $this->brand->get_by_id($brand_id);
            
            if ($brand) {
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($brand));
            } else {
                $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Brand not found']));
            }
        } catch (Exception $e) {
            log_message('error', 'Brand details error: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function get_brand_vouchers($brand_id) {
        try {
            $vouchers = $this->brand->get_brand_vouchers($brand_id);
            header('Content-Type: application/json');
            echo json_encode($vouchers);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function edit($id) {
        $data['title'] = "Edit Brand";
        $data['brand'] = $this->brand->get_by_id($id);

        if (!$data['brand']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
            redirect('brand');
        }

        $this->template->load('templates/dashboard', 'brand/edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('name', 'Nama Brand', 'required|trim');
        $this->form_validation->set_rules('desc', 'Deskripsi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Brand";
            $data['brand'] = $this->brand->get_by_id($id);
            $this->template->load('templates/dashboard', 'brand/edit', $data);
        } else {
            $data = [
                'name' => $this->input->post('name', true),
                'desc' => $this->input->post('desc', true),
                'instagram' => $this->input->post('instagram'),
                'tiktok' => $this->input->post('tiktok'),
                'wa' => $this->input->post('wa'),
                'web' => $this->input->post('web')
            ];

            // Handle logo upload
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/logo/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 10000;
                $config['file_name'] = 'logo-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('image')) {
                    // Delete old image
                    $old_image = $this->brand->get_by_id($id)['image'];
                    if ($old_image != null && file_exists('../ImageTerasJapan/logo/' . $old_image)) {
                        unlink('../ImageTerasJapan/logo/' . $old_image);
                    }
                    $data['image'] = $this->upload->data('file_name');
                }
            }

            // Handle banner upload
            if (!empty($_FILES['banner']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/banner/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 10000;
                $config['file_name'] = 'banner-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('banner')) {
                    // Delete old banner
                    $old_banner = $this->brand->get_by_id($id)['banner'];
                    if ($old_banner != null && file_exists('../ImageTerasJapan/banner/' . $old_banner)) {
                        unlink('../ImageTerasJapan/banner/' . $old_banner);
                    }
                    $data['banner'] = $this->upload->data('file_name');
                }
            }

            if ($this->brand->update($id, $data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Brand berhasil diupdate!</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Brand gagal diupdate!</div>');
            }
            redirect('brand');
        }
    }

    public function add() {
        $data['title'] = "Tambah Brand";
        $this->template->load('templates/dashboard', 'brand/add', $data);
    }

    public function save() {
        $this->form_validation->set_rules('name', 'Nama Brand', 'required|trim');
        $this->form_validation->set_rules('desc', 'Deskripsi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Brand";
            $this->template->load('templates/dashboard', 'brand/add', $data);
        } else {
            $data = [
                'name' => $this->input->post('name', true),
                'desc' => $this->input->post('desc', true), // Fixed the syntax error here
                'instagram' => $this->input->post('instagram'),
                'tiktok' => $this->input->post('tiktok'),
                'wa' => $this->input->post('wa'),
                'web' => $this->input->post('web')
            ];

            // Handle logo upload
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/logo/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 10000;
                $config['file_name'] = 'logo-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('image')) {
                    $data['image'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                    redirect('brand/add');
                }
            }

            // Handle banner upload
            if (!empty($_FILES['banner']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/banner/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 10000;
                $config['file_name'] = 'banner-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('banner')) {
                    $data['banner'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                    redirect('brand/add');
                }
            }

            if ($this->brand->insert($data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Brand berhasil ditambahkan!</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Brand gagal ditambahkan!</div>');
            }
            redirect('brand');
        }
    }

    public function addpromo($brand_id = null)
    {
        // Validate brand exists
        $brand = $this->brand->get_by_id($brand_id);
        if (!$brand) {
            set_pesan('Brand ID tidak ditemukan', false);
            redirect('brand');
        }

        if ($this->input->post()) {
            $this->_validasi_promo();
            
            if ($this->form_validation->run() == false) {
                $data['title'] = "Tambah Promo";
                $data['brand_id'] = $brand_id;
                $data['brand'] = $brand;
                $this->template->load('templates/dashboard', 'brand/add_promo', $data);
            } else {
                $input = $this->input->post(null, true);
                $input['id_brand'] = $brand_id;

                // Handle image upload
                $config['upload_path']   = '../ImageTerasJapan/promo/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size']      = 2048;
                $config['file_name']     = 'promo_' . time();

                $this->load->library('upload', $config);

                if (!empty($_FILES['promo_image']['name'])) {
                    if ($this->upload->do_upload('promo_image')) {
                        $input['promo_image'] = $this->upload->data('file_name');
                    } else {
                        set_pesan($this->upload->display_errors(), false);
                        redirect('brand/addpromo/' . $brand_id);
                    }
                }

                // Set status based on dates
                date_default_timezone_set('Asia/Jakarta');
                $now = strtotime('now');
                $available_from = strtotime($input['available_from']);
                $valid_until = strtotime($input['valid_until']);

                if ($available_from > $now) {
                    $input['status'] = 'Coming';
                } else if ($valid_until < $now) {
                    $input['status'] = 'Expired';
                } else {
                    $input['status'] = 'Available';
                }

                if ($this->brand->insert_promo($input)) {
                    set_pesan('Promo berhasil ditambahkan');
                    redirect('brand');
                } else {
                    set_pesan('Gagal menambahkan promo', false);
                    redirect('brand/addpromo/' . $brand_id);
                }
            }
        } else {
            $data['title'] = "Tambah Promo";
            $data['brand_id'] = $brand_id;
            $data['brand'] = $brand;
            $this->template->load('templates/dashboard', 'brand/add_promo', $data);
        }
    }

    public function editpromo($id = null)
    {
        if (!$id) {
            set_pesan('ID Promo tidak ditemukan', false);
            redirect('brand');
        }

        // Get promo data
        $promo = $this->brand->get_promo_by_id($id);
        if (!$promo) {
            set_pesan('Data promo tidak ditemukan', false);
            redirect('brand');
        }

        if ($this->input->post()) {
            $this->_validasi_promo();
            
            if ($this->form_validation->run() == false) {
                $data['title'] = "Edit Promo";
                $data['promo'] = $promo;
                $this->template->load('templates/dashboard', 'brand/edit_promo', $data);
            } else {
                $input = $this->input->post(null, true);
                
                // Handle image upload if new image is provided
                if (!empty($_FILES['promo_image']['name'])) {
                    $config['upload_path']   = '../ImageTerasJapan/promo/';
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['max_size']      = 2048;
                    $config['file_name']     = 'promo_' . time();

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('promo_image')) {
                        // Delete old image if exists
                        if ($promo['promo_image'] && file_exists($config['upload_path'] . $promo['promo_image'])) {
                            unlink($config['upload_path'] . $promo['promo_image']);
                        }
                        $input['promo_image'] = $this->upload->data('file_name');
                    } else {
                        set_pesan($this->upload->display_errors(), false);
                        redirect('brand/editpromo/' . $id);
                    }
                }

                // Set timezone and determine status
                date_default_timezone_set('Asia/Jakarta');
                $now = strtotime('now');
                $available_from = strtotime($input['available_from']);
                $valid_until = strtotime($input['valid_until']);

                if ($available_from > $now) {
                    $input['status'] = 'Coming';
                } else if ($valid_until < $now) {
                    $input['status'] = 'Expired';
                } else {
                    $input['status'] = 'Available';
                }

                if ($this->brand->save_edit_promo($id, $input)) {
                    set_pesan('Promo berhasil diupdate');
                    redirect('brand');
                } else {
                    set_pesan('Gagal mengupdate promo', false);
                    redirect('brand/editpromo/' . $id);
                }
            }
        } else {
            $data['title'] = "Edit Promo";
            $data['promo'] = $promo;
            $this->template->load('templates/dashboard', 'brand/edit_promo', $data);
        }
    }

    private function _validasi_promo()
    {
        $this->form_validation->set_rules('promo_name', 'Nama Promo', 'required|trim');
        $this->form_validation->set_rules('promo_desc', 'Deskripsi', 'required|trim');
        $this->form_validation->set_rules('available_from', 'Tersedia Sejak', 'required');
        $this->form_validation->set_rules('valid_until', 'Masa Berlaku', 'required');
        
        // Custom validation for dates
        if (!empty($this->input->post('available_from')) && !empty($this->input->post('valid_until'))) {
            $available_from = strtotime($this->input->post('available_from'));
            $valid_until = strtotime($this->input->post('valid_until'));
            
            if ($valid_until < $available_from) {
                $this->form_validation->set_message('_validasi_promo', 'Masa berlaku tidak boleh lebih awal dari tanggal tersedia');
                return false;
            }
        }
    }

    public function debug_brand($id) {
        // Remove in production
        $this->output->enable_profiler(TRUE);
        
        $brand = $this->brand->get_by_id($id);
        echo "<pre>";
        print_r($brand);
        echo "</pre>";
        
        // Show last query
        echo $this->db->last_query();
    }

    public function add_voucher($brand_id = null) {
        if (!$brand_id) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Brand ID tidak ditemukan!</div>');
            redirect('brand');
        }
    
        // Validate brand exists
        $brand = $this->brand->get_by_id($brand_id);
        if (!$brand) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Brand tidak ditemukan!</div>');
            redirect('brand');
        }
    
        $data['title'] = "Tambah Voucher";
        $data['brand'] = $brand;
        $data['brand_id'] = $brand_id;
        
        $this->template->load('templates/dashboard', 'brand/add_voucher', $data);
    }

    public function edit_voucher($id) {
        $data['title'] = "Edit Voucher";
        $data['voucher'] = $this->brand->get_voucher_by_id($id);
        
        if (!$data['voucher']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
            redirect('brand');
        }
        
        $this->form_validation->set_rules('title', 'Judul', 'required|trim');
        $this->form_validation->set_rules('points_required', 'Poin', 'required|numeric');
        $this->form_validation->set_rules('category', 'Kategori', 'required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');
        $this->form_validation->set_rules('valid_until', 'Berlaku Sampai', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required|numeric');
        
        if ($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'brand/edit_voucher', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Handle image upload if new image is provided
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/reward/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'voucher-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('image')) {
                    // Delete old image
                    $old_image = $data['voucher']['image_name'];
                    if ($old_image && file_exists($config['upload_path'] . $old_image)) {
                        unlink($config['upload_path'] . $old_image);
                    }
                    $input['image_name'] = $this->upload->data('file_name');
                }
            }
            
            // Calculate total days
            $now = time();
            $valid_until = strtotime($input['valid_until']);
            $input['total_days'] = ceil(($valid_until - $now) / (60 * 60 * 24));
            
            if ($this->brand->update_voucher($id, $input)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Voucher berhasil diupdate!</div>');
                redirect('brand');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Voucher gagal diupdate!</div>');
                redirect('brand/edit_voucher/' . $id);
            }
        }
    }

    public function delete_voucher($id) {
        $voucher = $this->brand->get_voucher_by_id($id);
        
        if ($voucher) {
            // Delete image if exists
            if ($voucher['image_name'] && file_exists('../ImageTerasJapan/reward/' . $voucher['image_name'])) {
                unlink('../ImageTerasJapan/reward/' . $voucher['image_name']);
            }
            
            if ($this->brand->delete_voucher($id)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Voucher berhasil dihapus!</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Voucher gagal dihapus!</div>');
            }
        }
        
        redirect('brand');
    }

    public function save_voucher() {
        $brand_id = $this->input->post('brand_id');
        
        if (!$brand_id) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Brand ID tidak ditemukan!</div>');
            redirect('brand');
        }
    
        // Validasi form
        $this->form_validation->set_rules('title', 'Judul Voucher', 'required|trim');
        $this->form_validation->set_rules('points_required', 'Poin', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('category', 'Kategori', 'required|in_list[newmember,oldmember,code]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');
        $this->form_validation->set_rules('valid_until', 'Berlaku Sampai', 'required');
        $this->form_validation->set_rules('qty', 'Jumlah Voucher', 'required|numeric|greater_than[0]');
    
        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Voucher";
            $data['brand_id'] = $brand_id;
            $data['brand'] = $this->brand->get_by_id($brand_id);
            $this->template->load('templates/dashboard', 'brand/add_voucher', $data);
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'points_required' => $this->input->post('points_required'),
                'category' => $this->input->post('category'),
                'description' => $this->input->post('description'),
                'valid_until' => $this->input->post('valid_until'),
                'qty' => $this->input->post('qty'),
                'brand_id' => $brand_id,
                'total_days' => ceil((strtotime($this->input->post('valid_until')) - time()) / (60 * 60 * 24))
            ];
    
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/reward/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 5120; // 5MB
                $config['file_name'] = 'voucher-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('image')) {
                    $data['image_name'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                    redirect('brand/add_voucher/' . $brand_id);
                }
            }
    
            if ($this->brand->insert_voucher($data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Voucher berhasil ditambahkan!</div>');
                redirect('brand');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal menambahkan voucher!</div>');
                redirect('brand/add_voucher/' . $brand_id);
            }
        }
    }

    public function update_voucher($id) {
        $voucher = $this->brand->get_voucher_by_id($id);
        
        if (!$voucher) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
            redirect('brand');
        }
    
        $this->form_validation->set_rules('title', 'Judul Voucher', 'required|trim');
        $this->form_validation->set_rules('points_required', 'Poin', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('category', 'Kategori', 'required|in_list[newmember,oldmember,code]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');
        $this->form_validation->set_rules('valid_until', 'Berlaku Sampai', 'required');
        $this->form_validation->set_rules('qty', 'Jumlah Voucher', 'required|numeric|greater_than[0]');
    
        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Voucher";
            $data['voucher'] = $voucher;
            $this->template->load('templates/dashboard', 'brand/edit_voucher', $data);
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'points_required' => $this->input->post('points_required'),
                'category' => $this->input->post('category'),
                'description' => $this->input->post('description'),
                'valid_until' => $this->input->post('valid_until'),
                'qty' => $this->input->post('qty'),
                'total_days' => ceil((strtotime($this->input->post('valid_until')) - time()) / (60 * 60 * 24))
            ];
    
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/reward/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 5120; // 5MB
                $config['file_name'] = 'voucher-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('image')) {
                    // Delete old image if exists
                    if ($voucher['image_name'] && file_exists($config['upload_path'] . $voucher['image_name'])) {
                        unlink($config['upload_path'] . $voucher['image_name']);
                    }
                    $data['image_name'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                    redirect('brand/edit_voucher/' . $id);
                }
            }
    
            if ($this->brand->update_voucher($id, $data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Voucher berhasil diupdate!</div>');
                redirect('brand');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal mengupdate voucher!</div>');
                redirect('brand/edit_voucher/' . $id);
            }
        }
    }
}