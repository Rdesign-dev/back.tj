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
                <?= form_open(); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="username">Username</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('username'); ?>" type="text" id="username" name="username" class="form-control" placeholder="Username">
                        <?= form_error('username', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="password">Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <?= form_error('password', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="password2">Konfirmasi Password</label>
                    <div class="col-md-6">
                        <input type="password" id="password2" name="password2" class="form-control" placeholder="Konfirmasi Password">
                        <?= form_error('password2', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('nama'); ?>" type="text" id="nama" name="nama" class="form-control" placeholder="Nama">
                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="no_telp">Nomor Telepon</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('no_telp'); ?>" type="text" id="no_telp" name="no_telp" class="form-control" placeholder="Nomor Telepon">
                        <?= form_error('no_telp', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="role">Role</label>
                    <div class="col-md-6">
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'kasir'); ?> value="kasir" type="radio" id="kasir" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="kasir">Kasir</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input <?= set_radio('role', 'admincabang'); ?> value="admincabang" type="radio" id="admincabang" name="role" class="custom-control-input">
                            <label class="custom-control-label" for="admincabang">Admin Cabang</label>
                        </div>
                        <?= form_error('role', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group" id="divCabang" style="display: none;">
                    <label class="col-md-4 text-md-right" for="idcabang">Cabang</label>
                    <div class="col-md-6">
                        <!-- Tambahkan input field untuk cabang di sini -->
                        <select name="idcabang" id="idcabang" class="form-control">
                        <option value="">- Pilih Cabang -</option>
                                <option value="<?= userdata('idcabang')?>"><?= userdata('namacabang')?></option>
                             
                        </select>
                        <?= form_error('cabang', '<span class="text-danger small">', '</span>'); ?>
                        <input type="hidden" name="namacabang" id="namacabang">
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