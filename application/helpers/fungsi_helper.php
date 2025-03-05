<?php

function cek_login()
{
    $ci = get_instance();
    if (!$ci->session->has_userdata('login_session')) {
        set_pesan('silahkan login.');
        redirect('auth');
    }
}

function is_admin()
{
    $ci = get_instance();
    $login_session = $ci->session->userdata('login_session');
    
    if (!$login_session || !isset($login_session['account_type'])) {
        return false;
    }
    
    return $login_session['account_type'] === 'super_admin';
}

function set_pesan($pesan, $tipe = true)
{
    $ci = get_instance();
    if ($tipe) {
        $ci->session->set_flashdata('pesan', "<div class='alert alert-success'><strong>SUCCESS!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
    } else {
        $ci->session->set_flashdata('pesan', "<div class='alert alert-danger'><strong>ERROR!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
    }
}

function userdata($field)
{
    $ci = get_instance();
    $login_session = $ci->session->userdata('login_session');
    
    if (!$login_session) {
        return null;
    }

    // Menyesuaikan field name dengan struktur baru
    switch($field) {
        case 'nama':
            return $login_session['name'] ?? null;
        case 'foto':
            return $login_session['photo'] ?? 'user.png';
        default:
            return $login_session[$field] ?? null;
    }
}

function output_json($data)
{
    $ci = get_instance();
    $data = json_encode($data);
    $ci->output->set_content_type('application/json')->set_output($data);
}
