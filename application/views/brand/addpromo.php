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
                        <a href="<?= base_url('brand') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('brand/addpromo/' . $brand_id); ?>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_name">Nama Promo</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('promo_name'); ?>" type="text" id="promo_name" name="promo_name" class="form-control" placeholder="Masukkan Nama Promo" required>
                        <?= form_error('promo_name', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_desc">Deskripsi Promo</label>
                    <div class="col-md-6">
                        <textarea id="promo_desc" name="promo_desc" class="form-control" placeholder="Masukkan Deskripsi Promo" rows="4"><?= set_value('promo_desc'); ?></textarea>
                        <?= form_error('promo_desc', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="available_from">Tersedia Sejak</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="available_from" name="available_from" class="form-control" value="<?= set_value('available_from'); ?>" required>
                        <?= form_error('available_from', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="valid_until">Masa Berlaku</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="valid_until" name="valid_until" class="form-control" value="<?= set_value('valid_until'); ?>" required>
                        <?= form_error('valid_until', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_image">Gambar Promo</label>
                    <div class="col-md-6">
                        <input type="file" id="promo_image" name="promo_image" class="form-control" accept="image/*">
                        <?= form_error('promo_image', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('brandForm');
    const requiredFields = ['promo_name', 'promo_desc', 'promo_image', 'available_from', 'valid_until'];
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>

