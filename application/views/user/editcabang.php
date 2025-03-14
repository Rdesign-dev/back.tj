<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Edit User
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('usercabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('', [], ['id' => $user['id']]); ?>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="username">Username</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input value="<?= set_value('username', $user['username']); ?>" name="username" id="username" type="text" class="form-control" placeholder="Username">
                        </div>
                        <?= form_error('username', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="Name">Nama</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input value="<?= set_value('Name', $user['Name']); ?>" name="Name" id="Name" type="text" class="form-control" placeholder="Nama">
                        </div>
                        <?= form_error('Name', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="phone_number">Nomor Telepon</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
                            </div>
                            <input value="<?= set_value('phone_number', $user['phone_number']); ?>" name="phone_number" id="phone_number" type="text" class="form-control" placeholder="Nomor Telepon">
                        </div>
                        <?= form_error('phone_number', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="account_type">Role</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-users"></i></span>
                            </div>
                            <select name="account_type" id="account_type" class="form-control">
                                <option value="cashier" <?= $user['account_type'] == 'cashier' ? 'selected' : ''; ?>>Kasir</option>
                                <option value="branch_admin" <?= $user['account_type'] == 'branch_admin' ? 'selected' : ''; ?>>Admin Cabang</option>
                            </select>
                        </div>
                        <?= form_error('account_type', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="status">Status</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-toggle-on"></i></span>
                            </div>
                            <select name="status" id="status" class="form-control">
                                <option value="Active" <?= $user['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?= $user['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <?= form_error('status', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right">Foto Saat Ini</label>
                    <div class="col-md-9">
                        <img src="<?= base_url('../ImageTerasJapan/Profpic/') . $user['photo'] ?>" alt="<?= $user['Name']; ?>" class="img-thumbnail mb-2" style="height: 100px">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="photo">Foto Baru</label>
                    <div class="col-md-9">
                        <input type="file" name="photo" id="photo" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin upload foto. Sistem akan menggunakan foto yang ada.</small>
                    </div>
                </div>
                <div class="row form-group justify-content-end">
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">
                                Simpan
                            </span>
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
$(document).ready(function(){
    // Tangkap perubahan pada radio button dengan name='role'
    $('input[name="role"]').change(function(){
        // Periksa apakah yang dipilih adalah 'kasir'
        if ($(this).val() === 'kasir') {
            // Jika 'kasir', tampilkan field cabang
            $('#divCabang').show();
        } else if($(this).val() === 'admincabang') {
            $('#divCabang').show();
        } else {
            // Jika 'admin' atau yang lainnya, sembunyikan field cabang
            $('#divCabang').hide();
        }
    });
        var initialRole = '<?= $user['role'] ?>';
        if (initialRole === 'kasir') {
            $('#divCabang').show();
            // Set the initial selected value for the dropdown
            $('#idcabang').val('<?= $user['idcabang'] ?>').change();
        } else if(initialRole === 'admincabang') {
            $('#divCabang').show();
            // Set the initial selected value for the dropdown
            $('#idcabang').val('<?= $user['idcabang'] ?>').change();
        }else {
            $('#divCabang').hide();
        }
    $('#idcabang').change(function(){
        // Ambil nilai ID cabang yang dipilih
        var selectedBranchId = $(this).val();

        // Temukan objek cabang berdasarkan ID
        var selectedBranch = <?php echo json_encode($cabang); ?>.find(function(cabang) {
            return cabang.id == selectedBranchId;
        });

        // Update value of the hidden input field with the branch name
        $('#namacabang').val(selectedBranch.namacabang); // Ganti 'nama_cabang' dengan kunci yang sesuai dalam objek cabang
    });

    // Validasi form
    $('form').submit(function(event) {
        var isValid = true;

        // Validasi input fields
        $(this).find('.form-control').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Validasi radio button
        if ($('input[name="role"]:checked').length === 0) {
            $('.custom-control').addClass('is-invalid');
            isValid = false;
        } else {
            $('.custom-control').removeClass('is-invalid');
        }

        // Validasi cabang untuk role 'kasir'
        if ($('input[name="role"]:checked').val() === 'kasir' && $('#idcabang').val() === '') {
            $('#divCabang').addClass('is-invalid');
            isValid = false;
        } else if($('input[name="role"]:checked').val() === 'admincabang' && $('#idcabang').val() === '') {
            $('#divCabang').addClass('is-invalid');
            isValid = false;
        }else {
            $('#divCabang').removeClass('is-invalid');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>