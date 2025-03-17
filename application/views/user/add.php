<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form <?= $title; ?>
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('user') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Kembali
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('', array('enctype' => 'multipart/form-data')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="username">Username</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('username'); ?>" type="text" id="username" name="username"
                            class="form-control" placeholder="Username">
                        <?= form_error('username', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="password">Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Password">
                        <?= form_error('password', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="password2">Konfirmasi Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password2" name="password2" class="form-control"
                            placeholder="Konfirmasi Password">
                        <?= form_error('password2', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="Name">Nama</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('Name'); ?>" type="text" id="Name" name="Name" class="form-control"
                            placeholder="Nama Lengkap">
                        <?= form_error('Name', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="phone_number">Nomor Telepon</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('phone_number'); ?>" type="text" id="phone_number"
                            name="phone_number" class="form-control" placeholder="Nomor Telepon">
                        <?= form_error('phone_number', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="account_type">Role</label>
                    <div class="col-md-6">
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('account_type', 'super_admin'); ?> value="super_admin" type="radio"
                                id="super_admin" name="account_type" class="custom-control-input">
                            <label class="custom-control-label" for="super_admin">Super Admin</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('account_type', 'admin_central'); ?> value="admin_central" type="radio"
                                id="admin_central" name="account_type" class="custom-control-input">
                            <label class="custom-control-label" for="admin_central">Admin Pusat</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('account_type', 'branch_admin'); ?> value="branch_admin" type="radio"
                                id="branch_admin" name="account_type" class="custom-control-input">
                            <label class="custom-control-label" for="branch_admin">Admin Cabang</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('account_type', 'cashier'); ?> value="cashier" type="radio"
                                id="cashier" name="account_type" class="custom-control-input">
                            <label class="custom-control-label" for="cashier">Kasir</label>
                        </div>
                        <?= form_error('account_type', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group" id="divCabang" style="display: none;">
                    <label class="col-md-4 text-md-right" for="branch_id">Cabang</label>
                    <div class="col-md-6">
                        <select name="branch_id" id="branch_id" class="form-control">
                            <option value="">- Pilih Cabang -</option>
                            <?php foreach ($cabang as $cbg) : ?>
                            <option value="<?= $cbg['id'] ?>"><?= $cbg['branch_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('branch_id', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="status">Status</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control">
                            <option value="Inactive">Tidak Aktif</option>
                            <option value="Active">Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="photo">Foto</label>
                    <div class="col-md-6">
                        <input type="file" id="photo" name="photo" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin upload foto. Sistem akan menggunakan foto
                            default.</small>
                    </div>
                </div>
                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            Reset
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $('input[name="account_type"]').change(function() {
        if ($(this).val() === 'cashier' || $(this).val() === 'branch_admin') {
            $('#divCabang').show();
        } else {
            $('#divCabang').hide();
            $('#branch_id').val('');
        }
    });
});
</script>