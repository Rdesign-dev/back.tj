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
                        <a href="<?= base_url('iklan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('iklan/edit_iklan/' . $iklan['id'], [], ['id' => $iklan['id']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Nama Promo</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $iklan['title']); ?>" class="form-control" placeholder="Masukkan Nama Promo">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="description" name="description" class="form-control" placeholder="Masukkan Deskripsi Promo"><?= set_value('description', $iklan['description']); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="foto">Gambar Promo</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <img src="https://terasjapan.com/ImageTerasJapan/promo/<?= $iklan['image_name'] ?>" 
                                     alt="Promo Image" 
                                     class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                <?= form_error('foto', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
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
            </div>
        </div>
    </div>
</div>
