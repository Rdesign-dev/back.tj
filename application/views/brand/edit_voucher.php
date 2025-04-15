<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Voucher
                        </h4>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('brand/update_voucher/'.$voucher['id']); ?>
                <input type="hidden" name="brand_id" value="<?= $voucher['brand_id'] ?>">

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Judul Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('title', $voucher['title']); ?>" 
                               type="text" id="title" name="title" 
                               class="form-control" placeholder="Judul Voucher">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="points_required">Poin yang Dibutuhkan</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('points_required', $voucher['points_required']); ?>" 
                               min="1" type="number" id="points_required" 
                               name="points_required" class="form-control">
                        <?= form_error('points_required', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="category">Kategori</label>
                    <div class="col-md-6">
                        <select name="category" id="category" class="form-control">
                            <option value="newmember" <?= $voucher['category'] == 'newmember' ? 'selected' : ''; ?>>Member Baru</option>
                            <option value="oldmember" <?= $voucher['category'] == 'oldmember' ? 'selected' : ''; ?>>Member Biasa</option>
                            <option value="code" <?= $voucher['category'] == 'code' ? 'selected' : ''; ?>>Kode Referal</option>
                        </select>
                        <?= form_error('category', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea name="description" id="description" 
                                  class="form-control" rows="4"><?= set_value('description', $voucher['description']); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="valid_until">Berlaku Sampai</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('valid_until', date('Y-m-d\TH:i', strtotime($voucher['valid_until']))); ?>" 
                               type="datetime-local" id="valid_until" 
                               name="valid_until" class="form-control">
                        <?= form_error('valid_until', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="qty">Jumlah Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('qty', $voucher['qty']); ?>" 
                               min="1" type="number" id="qty" 
                               name="qty" class="form-control">
                        <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar Voucher</label>
                    <div class="col-md-6">
                        <?php if($voucher['image_name']): ?>
                            <div class="mb-2">
                                <img src="https://terasjapan.com/ImageTerasJapan/reward/<?= $voucher['image_name'] ?>" 
                                     alt="Current Image" class="img-thumbnail" style="max-height: 150px">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" 
                               class="form-control-file" 
                               accept="image/gif,image/jpeg,image/png">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Update</span>
                        </button>
                        <a href="<?= base_url('brand'); ?>" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>