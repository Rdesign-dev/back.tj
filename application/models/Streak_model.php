<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Streak_model extends CI_Model
{
    public function getDailyRewards()
    {
        return $this->db->get('daily_login_rewards')->result();
    }

    public function updateReward($id, $points)
    {
        $this->db->where('id', $id);
        return $this->db->update('daily_login_rewards', ['points' => $points]);
    }
}