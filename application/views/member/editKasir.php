<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 mb-4" style="background: rgba(255,255,255,0.7);">
            <div class="card-header border-0 py-3" style="background: rgba(255,255,255,0.4);">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Edit Data Member
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('member/indexKasir') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?php if ($member): ?>
                    <?= form_open_multipart('', [], ['nomor' => $member['nomor']]); ?>
                    <div class="row form-group">
                        <label class="col-md-3 text-md-right" for="foto">Foto</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12">
                                    <img src="https://terasjapan.com/ImageTerasJapan/ProfPic/<?= $member['foto'] ?>" 
                                         alt="User" class="rounded-circle shadow-sm img-thumbnail" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="col-12 mt-2">
                                    <input type="file" name="foto" id="foto" class="form-control">
                                    <?= form_error('foto', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="namamember">Nama Member</label>
                        <div class="col-md-6">
                            <input type="text" id="namamember" name="namamember" value="<?= set_value('namamember', $member['namamember']); ?>" class="form-control" placeholder="Masukkan Nama Member">
                            <?= form_error('namamember', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="nomor">Nomor Handphone</label>
                        <div class="col-md-6">
                            <input type="text" id="nomor" name="nomor" class="form-control" placeholder="nomor" value="<?= set_value('nomor', $member['nomor']); ?>">
                            <?= form_error('nomor', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="alamat">Alamat</label>
                        <div class="col-md-6">
                            <textarea name="alamat" id="alamat" cols="20" class="form-control" rows="10"><?= set_value('alamat', $member['alamat']); ?></textarea>
                            <?= form_error('alamat', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="email">Email</label>
                        <div class="col-md-6">
                            <input type="email" id="email" name="email" class="form-control" placeholder="email" value="<?= set_value('email', $member['email']); ?>">
                            <?= form_error('email', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="jeniskelamin">Jenis Kelamin</label>
                        <div class="col-md-6">
                            <select name="jeniskelamin" id="jeniskelamin" class="form-control">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= ($member['jeniskelamin'] == 'male') ? 'selected' : ''; ?>>Laki-Laki</option>
                                <option value="P" <?= ($member['jeniskelamin'] == 'female') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                            <?= form_error('jeniskelamin', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="tanggallahir">Tanggal Lahir</label>
                        <div class="col-md-6">
                            <input type="date" id="tanggallahir" name="tanggallahir" class="form-control" value="<?= set_value('tanggallahir', $member['tanggallahir']); ?>">
                            <?= form_error('tanggallahir', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="tempatlahir">Kota</label>
                        <div class="col-md-6">
                            <input type="text" id="tempatlahir" name="tempatlahir" class="form-control" placeholder="Masukkan Kota" value="<?= set_value('tempatlahir', $member['tempatlahir']); ?>">
                            <?= form_error('tempatlahir', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>

                    <div class="row form-group justify-content-end">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon"><i class="fa fa-save"></i></span>
                                <span class="text">Update</span>
                            </button>
                            <button type="reset" class="btn btn-secondary btn-icon-split">
                                <span class="icon"><i class="fas fa-backspace"></i></span>
                                <span class="text">Reset</span>
                            </button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                <?php else: ?>
                    <div class="text-center">
                        <p>Data member tidak ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Menambahkan event listener untuk pengiriman formulir
        document.querySelector('form').addEventListener('submit', function (event) {
            // Memvalidasi formulir sebelum pengiriman
            if (!validateForm()) {
                event.preventDefault(); // Mencegah pengiriman formulir jika validasi gagal
            }
        });
    });

    function validateForm() {
        let isValid = true;

        // Mereset gaya dari validasi sebelumnya
        resetValidationStyles();

        // Memeriksa setiap kolom input
        const formElements = document.querySelectorAll('input, textarea, select');
        formElements.forEach(function (element) {
            if (!element.value.trim()) {
                // Jika kolom kosong, tandai sebagai tidak valid
                markAsInvalid(element);
                isValid = false;
            }
        });

        return isValid;
    }

    function markAsInvalid(element) {
        // Menambahkan batas merah dan tanda 'x' ke kolom input
        element.classList.add('is-invalid');
        const errorIcon = document.createElement('span');
        // Memeriksa apakah tanda 'x' sudah ada sebelumnya
        if (!element.parentElement.querySelector('.text-danger')) {
            element.parentNode.appendChild(errorIcon);
        }
    }

    function resetValidationStyles() {
        // Menghapus batas merah dan 'x' dari semua elemen
        const invalidElements = document.querySelectorAll('.is-invalid');
        invalidElements.forEach(function (element) {
            element.classList.remove('is-invalid');
        });

        const errorIcons = document.querySelectorAll('.text-danger');
        errorIcons.forEach(function (icon) {
            icon.remove();
        });
    }
</script>
