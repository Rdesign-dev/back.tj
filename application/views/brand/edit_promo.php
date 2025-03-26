<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Edit Promo
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
                <?= form_open_multipart('brand/editpromo/' . $promo['id']); ?>
                <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                <input type="hidden" name="id_brand" value="<?= $promo['id_brand'] ?>">

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_name">Nama Promo</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('promo_name', $promo['promo_name']); ?>" type="text" id="promo_name" name="promo_name" class="form-control" required>
                        <?= form_error('promo_name', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_desc">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="promo_desc" name="promo_desc" class="form-control" rows="4" required><?= set_value('promo_desc', $promo['promo_desc']); ?></textarea>
                        <?= form_error('promo_desc', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="available_from">Tersedia Sejak</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="available_from" name="available_from" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($promo['available_from'])); ?>" required>
                        <?= form_error('available_from', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="valid_until">Masa Berlaku</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="valid_until" name="valid_until" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($promo['valid_until'])); ?>" required>
                        <?= form_error('valid_until', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="promo_image">Gambar Promo</label>
                    <div class="col-md-6">
                        <input type="file" id="promo_image" name="promo_image" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan Perubahan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>