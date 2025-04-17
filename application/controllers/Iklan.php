<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Iklan_model','iklan');
        cek_login();
    }

    public function tambahs(){
        $this->form_validation->set_rules('link', 'Link Promosi', 'required|trim');
        
        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Iklan";
            $this->template->load('templates/dashboard', 'iklan/add', $data);
        } else {
            $config['upload_path'] = FCPATH . '../ImageTerasJapan/promo';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|avif';
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('foto')) {
                $data = [
                    'title' => $this->input->post('link', true),
                    'image_name' => $this->upload->data('file_name'),
                    'description' => '' // Bisa ditambahkan jika diperlukan
                ];
                $this->db->insert('promo', $data);
                set_pesan('data berhasil disimpan');
            }
            redirect('iklan');
        }
    }

    public function index()
    {
        $data['title'] = "Promo Mingguan";
        $data['iklans'] = $this->db->select('id, title, description, image_name, status') // tadi kurang column status na teu kabawa
                                   ->from('promo')
                                   ->get()
                                   ->result_array();
		// $test = json_encode($data['iklans']);
		// var_dump($test);
		// die();
        $this->template->load('templates/dashboard', 'iklan/index', $data);
    }

    public function tambah_save()
    {
        $this->form_validation->set_rules('title', 'Nama Promo', 'required|trim');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');
        
        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Promo";
            $this->template->load('templates/dashboard', 'iklan/add', $data);
        } else {
            $upload_path = FCPATH . '../ImageTerasJapan/promo/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = '2048';  // 2MB max
            $config['encrypt_name']  = TRUE;     // Mengenkripsi nama file
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                
                $data = [
                    'title'       => $this->input->post('title', true),
                    'description' => $this->input->post('description', true),
                    'image_name'  => $upload_data['file_name']
                ];
                
                $this->db->insert('promo', $data);
                set_pesan('data berhasil disimpan');
                redirect('iklan');
            } else {
                set_pesan($this->upload->display_errors(), false);
                redirect('iklan/tambah');
            }
        }
    }

    public function edit($id){
        $data['title'] = "Edit Iklan Promosi";
        $id = $this->uri->segment('3');
    
        if (!empty($id)) {
            $data['iklan'] = $this->iklan->cari_detail_id($id);
    
            // Pastikan member ditemukan sebelum memuat template
            if ($data['iklan']) {
                // Load template dengan data yang telah disiapkan
                $this->template->load('templates/dashboard', 'iklan/edit', $data);
            } else {
                $this->session->set_flashdata('pesan','<div class="alert alert-error" role="alert">Data Tidak Ditemukan</div>');
                redirect(base_url('iklan'));
            }
        } else {
            $this->session->set_flashdata('pesan','<div class="alert alert-error" role="alert">Data Tidak Valid</div>');
                redirect(base_url('iklan'));
        }
        }
        public function edit_iklan($getId)
        {
            $id = encode_php_tags($getId);
            $this->form_validation->set_rules('title', 'Nama Promo', 'required|trim');
            $this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');
    
            if ($this->form_validation->run() == false) {
                $data['title'] = "Edit Promo";
                $data['iklan'] = $this->db->get_where('promo', ['id' => $id])->row_array();
                $this->template->load('templates/dashboard', 'iklan/edit', $data);
            } else {
                $data = [
                    'title' => $this->input->post('title', true),
                    'description' => $this->input->post('description', true)
                ];
    
                if ($_FILES['foto']['name']) {
                    $upload_path = FCPATH . '../ImageTerasJapan/promo/';
                    
                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }
    
                    $config['upload_path']   = $upload_path;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size']      = '2048';
                    $config['encrypt_name']  = TRUE;
                    
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('foto')) {
                        $old_image = $this->db->get_where('promo', ['id' => $id])->row_array()['image_name'];
                        if ($old_image != 'default.png') {
                            $old_file = $upload_path . $old_image;
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                        $data['image_name'] = $this->upload->data('file_name');
                    }
                }
    
                $this->db->where('id', $id);
                $this->db->update('promo', $data);
                set_pesan('data berhasil diubah');
                redirect('iklan');
            }
        }
    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        $upload_path = FCPATH . '../ImageTerasJapan/promo/';
        $foto = $this->db->get_where('promo', ['id' => $id])->row_array()['image_name'];
        
        if ($foto != 'default.png') {
            $file_to_delete = $upload_path . $foto;
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
        }
        
        $this->db->delete('promo', ['id' => $id]);
        set_pesan('data berhasil dihapus');
        redirect('iklan');
    }

    public function toggle_status($getId)
    {
        $id = encode_php_tags($getId);
        
        // Get current status
        $promo = $this->db->get_where('promo', ['id' => $id])->row_array();
        if (!$promo) {
            set_pesan('Data promo tidak ditemukan', false);
            redirect('iklan');
        }
        
        // Toggle status
        $new_status = ($promo['status'] == 'Active') ? 'Inactive' : 'Active';
        
        $this->db->where('id', $id);
        $this->db->update('promo', ['status' => $new_status]);
        
        set_pesan('Status promo berhasil diubah');
        redirect('iklan');
    }
}