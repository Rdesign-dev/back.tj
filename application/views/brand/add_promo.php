<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Tambah Promo
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
                <input type="hidden" name="id_brand" value="<?= $brand_id ?>">

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_name">Nama Promo</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('promo_name'); ?>" type="text" id="promo_name" name="promo_name" class="form-control" placeholder="Nama Promo">
                        <?= form_error('promo_name', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_desc">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="promo_desc" name="promo_desc" class="form-control" rows="4"><?= set_value('promo_desc'); ?></textarea>
                        <?= form_error('promo_desc', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="points_required">Points Required</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('points_required'); ?>" type="number" id="points_required" name="points_required" class="form-control" min="0">
                        <?= form_error('points_required', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="qty">Quantity</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('qty'); ?>" type="number" id="qty" name="qty" class="form-control" min="0">
                        <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="available_from">Tersedia Sejak</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="available_from" name="available_from" class="form-control" min="<?= date('Y-m-d\TH:i'); ?>">
                        <?= form_error('available_from', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="valid_until">Masa Berlaku</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="valid_until" name="valid_until" class="form-control">
                        <?= form_error('valid_until', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_image">Gambar Promo</label>
                    <div class="col-md-6">
                        <input type="file" id="promo_image" name="promo_image" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Format: JPG, JPEG, PNG</small>
                        <?= form_error('promo_image', '<span class="text-danger small">', '</span>'); ?>
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
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for available_from to current date/time
    const now = new Date().toISOString().slice(0, 16);
    document.getElementById('available_from').min = now;

    // Ensure valid_until is after available_from
    document.getElementById('available_from').addEventListener('change', function() {
        document.getElementById('valid_until').min = this.value;
    });
});
</script>