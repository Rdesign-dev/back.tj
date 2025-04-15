<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loginstreak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Streak_model');
    }

    public function index()
    {
        $data['title'] = 'Daily Login Rewards Settings';
        $data['rewards'] = $this->Streak_model->getDailyRewards();
        
        // Load the streak view into a variable
        $data['contents'] = $this->load->view('member/streak', $data, true);
        
        // Load the dashboard template with all data
        $this->load->view('templates/dashboard', $data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $points = $this->input->post('points');
        
        $this->Streak_model->updateReward($id, $points);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Point berhasil di ubah!</div>');
        redirect('loginstreak');
    }
}