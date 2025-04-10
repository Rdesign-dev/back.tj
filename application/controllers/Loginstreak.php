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
        
        // $this->load->view('templates/header', $data);
        $this->load->view('member/streak', $data);
        // $this->load->view('templates/footer');
    }

    public function update()
    {
        $id = $this->input->post('id');
        $points = $this->input->post('points');
        
        $this->Streak_model->updateReward($id, $points);
        $this->session->set_flashdata('pesan', 'Points updated successfully!');
        redirect('loginstreak');
    }
}