<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Content_model', 'content');
    }

    public function index() {
        $data['title'] = "Content Management";
        $data['content'] = $this->content->get_all_content();
        $this->template->load('templates/dashboard', 'content/data', $data);
    }

    public function add() {
        $this->_validasi();
        
        if ($this->form_validation->run() == false) {
            $data['title'] = "Add Content";
            $this->template->load('templates/dashboard', 'content/add', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Get file extension
            $file_ext = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
            
            // Create filename from name field and timestamp
            $filename = url_title($input['name'], 'dash', true) . '_' . time() . '.' . $file_ext;
            
            $config['upload_path']      = '../ImageTerasJapan/contentpopup/';
            $config['allowed_types']    = 'gif|jpg|jpeg|png|JPEG|PNG';
            $config['file_name']        = $filename; // Set custom filename
            $config['max_size']         = '2048';
            $config['overwrite']        = true;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('Image')) {
                set_pesan('Error: ' . $this->upload->display_errors(), false);
                redirect('content/add');
            } else {
                $input['Image'] = $filename;
                $input['status'] = 'Active';

                $save = $this->content->insert_content($input);
                if ($save) {
                    set_pesan('Data berhasil disimpan');
                    redirect('content');
                } else {
                    set_pesan('Gagal menyimpan data', false);
                    redirect('content/add');
                }
            }
        }
    }

    public function edit($getId) {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Content";
            $data['content'] = $this->content->get_content_by_id($id);
            $this->template->load('templates/dashboard', 'content/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            
            if (!empty($_FILES['Image']['name'])) {
                // Get file extension
                $file_ext = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
                
                // Create filename from name field and timestamp
                $filename = url_title($input['name'], 'dash', true) . '_' . time() . '.' . $file_ext;
                
                $config['upload_path']      = '../ImageTerasJapan/contentpopup/';
                $config['allowed_types']    = 'gif|jpg|jpeg|png|JPEG|PNG';
                $config['file_name']        = $filename; // Set custom filename
                $config['max_size']         = '2048';
                $config['overwrite']        = true;

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('Image')) {
                    $old_image = $this->content->get_content_by_id($id)['Image'];
                    $file_path = '../ImageTerasJapan/contentpopup/' . $old_image;
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                    $input['Image'] = $filename;
                } else {
                    set_pesan('Error: ' . $this->upload->display_errors(), false);
                    redirect('content/edit/' . $id);
                }
            }

            $update = $this->content->update_content($id, $input);
            if ($update) {
                set_pesan('Data berhasil diupdate');
                redirect('content');
            } else {
                set_pesan('Gagal mengupdate data', false);
                redirect('content/edit/' . $id);
            }
        }
    }

    private function _validasi() 
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        // Remove or comment out link validation since it's optional
        // $this->form_validation->set_rules('link', 'Link', 'required|trim');
    }

    public function toggle($getId) {
        $id = encode_php_tags($getId);
        $toggle = $this->content->toggle_status($id);
        if ($toggle) {
            set_pesan('Status berhasil diubah.');
        } else {
            set_pesan('Gagal mengubah status.', false);
        }
        redirect('content');
    }

    public function delete($getId)
    {
        if (!$getId) {
            redirect('content');
        }
        
        $id = encode_php_tags($getId);
        
        // Get content details first to get image filename
        $content = $this->content->get_content_by_id($id);
        
        if ($content) {
            // Delete image file if exists
            if ($content['Image'] !== null) {
                $file_path = '../ImageTerasJapan/contentpopup/' . $content['Image'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Delete database record
            $delete = $this->content->delete_content($id);
            
            if ($delete) {
                set_pesan('Content berhasil dihapus.');
            } else {
                set_pesan('Gagal menghapus content.', false);
            }
        } else {
            set_pesan('Content tidak ditemukan.', false);
        }
        
        redirect('content');
    }
}
