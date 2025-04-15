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
                <?= form_open_multipart('brand/update/' . $brand['id'], array('id' => 'brandForm')); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="name">Nama Brand</label>
                    <div class="col-md-6">
                        <input type="text" id="name" name="name" value="<?= set_value('name', $brand['name']); ?>" class="form-control" placeholder="Masukkan Nama Brand">
                        <?= form_error('name', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="desc">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="desc" name="desc" class="form-control" placeholder="Masukkan Deskripsi Brand"><?= set_value('desc', $brand['desc']); ?></textarea>
                        <?= form_error('desc', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Logo Brand</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="<?= base_url('https://terasjapan.com/ImageTerasJapan/logo/' . $brand['image']) ?>" class="img-thumbnail">
                            </div>
                            <div class="col">
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="banner">Banner Brand</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="<?= base_url('https://terasjapan.com/ImageTerasJapan/banner/' . $brand['banner']) ?>" class="img-thumbnail">
                            </div>
                            <div class="col">
                                <input type="file" id="banner" name="banner" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah banner</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="instagram">Link Instagram</label>
                    <div class="col-md-6">
                        <input type="text" id="instagram" name="instagram" value="<?= set_value('instagram', $brand['instagram']); ?>" class="form-control" placeholder="Masukkan Link Instagram">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tiktok">Link Tiktok</label>
                    <div class="col-md-6">
                        <input type="text" id="tiktok" name="tiktok" value="<?= set_value('tiktok', $brand['tiktok']); ?>" class="form-control" placeholder="Masukkan Link Tiktok">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="wa">Link WhatsApp</label>
                    <div class="col-md-6">
                        <input type="text" id="wa" name="wa" value="<?= set_value('wa', $brand['wa']); ?>" class="form-control" placeholder="Masukkan Link WhatsApp">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="web">Link Website</label>
                    <div class="col-md-6">
                        <input type="text" id="web" name="web" value="<?= set_value('web', $brand['web']); ?>" class="form-control" placeholder="Masukkan Link Website">
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Update</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
