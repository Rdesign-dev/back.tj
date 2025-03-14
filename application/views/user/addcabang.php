<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Tambah User
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
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('', array('class' => 'form-horizontal')); ?>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="Name">Nama</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input value="<?= set_value('Name'); ?>" name="Name" id="Name" type="text" class="form-control" placeholder="Nama">
                        </div>
                        <?= form_error('Name', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="username">Username</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input value="<?= set_value('username'); ?>" name="username" id="username" type="text" class="form-control" placeholder="Username">
                        </div>
                        <?= form_error('username', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="phone_number">Nomor Telepon</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-phone"></i></span>
                            </div>
                            <input value="<?= set_value('phone_number'); ?>" name="phone_number" id="phone_number" type="number" class="form-control" placeholder="Nomor Telepon">
                        </div>
                        <?= form_error('phone_number', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="password">Password</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-lock"></i></span>
                            </div>
                            <input name="password" id="password" type="password" class="form-control" placeholder="Password">
                        </div>
                        <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="password2">Konfirmasi Password</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-lock"></i></span>
                            </div>
                            <input name="password2" id="password2" type="password" class="form-control" placeholder="Konfirmasi Password">
                        </div>
                        <?= form_error('password2', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="account_type">Role</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-id-card"></i></span>
                            </div>
                            <select name="account_type" id="account_type" class="form-control">
                                <option value="" selected disabled>Pilih Role</option>
                                <option value="cashier">Kasir</option>
                                <option value="branch_admin">Admin Cabang</option>
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
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-toggle-on"></i></span>
                            </div>
                            <select name="status" id="status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <?= form_error('status', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="photo">Foto</label>
                    <div class="col-md-9">
                        <input type="file" name="photo" id="photo" class="form-control">
                        <small class="text-muted mt-2 d-block">Kosongkan jika tidak ingin upload foto. Sistem akan menggunakan foto default.</small>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to toggle visibility of Cabang field based on the selected role
        function toggleCabangVisibility() {
            if ($('input[name="role"]:checked').val() === 'kasir') {
                $('#divCabang').show();
            } else if($('input[name="role"]:checked').val() === 'admincabang') {
                $('#divCabang').show();
            }else {
                $('#divCabang').hide();
            }
        }

        // Initial visibility setup
        toggleCabangVisibility();

        // Event handler for role change
        $('input[name="role"]').change(function () {
            toggleCabangVisibility();
        });

        // Event handler for page load
        $(window).on('load', function () {
            toggleCabangVisibility();
        });

        const form = document.querySelector('form');

        form.addEventListener('submit', function (event) {
            const inputs = form.querySelectorAll('input, select');
            let isValid = true;

            inputs.forEach(input => {
                const id = input.getAttribute('id');
                const errorId = 'error-' + id;

                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add("is-invalid");
                    $('#' + errorId).html('<i class="fas fa-times-circle"></i> Field is required.');
                } else {
                    input.classList.remove("is-invalid");
                    $('#' + errorId).html('');
                }
            });

            // Additional check for 'kasir' role
            const roleInput = document.querySelector('input[name="role"]:checked');
            const cabangInput = document.getElementById('idcabang');
            if (roleInput && roleInput.value === 'kasir' && !cabangInput.value.trim()) {
                isValid = false;
                $('#error-idcabang').html('<i class="fas fa-times-circle"></i> Cabang is required for Kasir role.');
                cabangInput.classList.add("is-invalid");
            }else if(roleInput && roleInput.value === 'admincabang' && !cabangInput.value.trim()){
                isValid = false;
                $('#error-idcabang').html('<i class="fas fa-times-circle"></i> Cabang is required for Admincabang role.');
                cabangInput.classList.add("is-invalid");
            } else {
                $('#error-idcabang').html('');
                cabangInput.classList.remove("is-invalid");
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
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
    });
</script>