<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Tambah Voucher Brand
                        </h4>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('brand/save_voucher'); ?>
                <input type="hidden" name="brand_id" value="<?= $brand_id ?>">

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Brand</label>
                    <div class="col-md-6">
                        <input type="hidden" name="brand_id" value="<?= $brand_id ?>">
                        <input type="text" class="form-control" value="<?= $brand['name'] ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Judul Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('title'); ?>" type="text" id="title" name="title" class="form-control" placeholder="Judul Voucher">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="points_required">Poin yang Dibutuhkan</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('points_required', '1'); ?>" min="1" type="number" id="points_required" name="points_required" class="form-control" placeholder="Minimal 1 Poin">
                        <?= form_error('points_required', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="category">Kategori</label>
                    <div class="col-md-6">
                        <select name="category" id="category" class="form-control">
                            <option value="newmember" <?= set_select('category', 'newmember'); ?>>Member Baru</option>
                            <option value="oldmember" <?= set_select('category', 'oldmember'); ?>>Member Biasa</option>
                            <option value="code" <?= set_select('category', 'code'); ?>>Kode Referal</option>
                        </select>
                        <?= form_error('category', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="Deskripsi Voucher"><?= set_value('description'); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="valid_until">Berlaku Sampai</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('valid_until'); ?>" type="datetime-local" id="valid_until" name="valid_until" class="form-control">
                        <?= form_error('valid_until', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="qty">Jumlah Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('qty', '1'); ?>" min="1" type="number" id="qty" name="qty" class="form-control" placeholder="Jumlah Voucher">
                        <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar Voucher</label>
                    <div class="col-md-6">
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/gif,image/jpeg,image/png">
                        <small class="text-muted">Max size 5MB. Format: gif|jpg|png|jpeg</small>
                        <?= form_error('image', '<span class="text-danger small">', '</span>'); ?>
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