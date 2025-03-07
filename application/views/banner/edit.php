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
                        <a href="<?= base_url('banner') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('banner/update/' . $banner['id'], [], ['id' => $banner['id']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Nama Banner</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $banner['title']); ?>" class="form-control" placeholder="Masukkan Nama Banner">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="link">Link Banner</label>
                    <div class="col-md-6">
                        <input type="text" id="link" name="link" value="<?= set_value('link', $banner['link']); ?>" class="form-control" placeholder="Masukkan Link Banner">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar Banner</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <img src="<?= base_url('../ImageTerasJapan/banner/' . $banner['image']) ?>" alt="Banner Image" class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                <?= form_error('image', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="status">Status</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control">
                            <option value="Active" <?= $banner['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $banner['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Update</span>
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