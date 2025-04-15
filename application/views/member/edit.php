<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Member
                        </h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('member/edit/' . $member['id'], ['id' => 'editForm']); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="foto">Foto</label>
                    <div class="col-md-6">
                        <img src="https://terasjapan.com/ImageTerasJapan/ProfPic/<?= $member['profile_pic'] ?? 'default.png' ?>" 
                             alt="Profile Picture" width="100" class="mb-3">
                        <input type="file" name="foto" id="foto" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('nama', $member['name']); ?>" type="text" id="nama" name="nama" class="form-control" placeholder="Nama Member">
                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="phone">Nomor Handphone</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('phone', $member['phone_number']); ?>" type="text" id="phone" name="phone" class="form-control" placeholder="Nomor Handphone">
                        <?= form_error('phone', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="alamat">Alamat</label>
                    <div class="col-md-6">
                        <textarea id="alamat" name="alamat" class="form-control" placeholder="Alamat"><?= set_value('alamat', $member['address']); ?></textarea>
                        <?= form_error('alamat', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="email">Email</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('email', $member['email']); ?>" type="email" id="email" name="email" class="form-control" placeholder="Email">
                        <?= form_error('email', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="gender">Jenis Kelamin</label>
                    <div class="col-md-6">
                        <select id="gender" name="gender" class="form-control">
                            <option value="">- Pilih -</option>
                            <option value="male" <?= $member['gender'] == 'male' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="female" <?= $member['gender'] == 'female' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                        <?= form_error('gender', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="birthdate">Tanggal Lahir</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('birthdate', $member['birthdate']); ?>" type="date" id="birthdate" name="birthdate" class="form-control">
                        <?= form_error('birthdate', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="city">Kota</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('city', $member['city']); ?>" type="text" id="city" name="city" class="form-control" placeholder="Kota">
                        <?= form_error('city', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <input type="hidden" name="old_image" value="<?= $member['profile_pic'] ?>">
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="reset" class="btn btn-secondary btn-sm">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- Skrip yang diperbarui di akhir berkas PHP Anda -->
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
