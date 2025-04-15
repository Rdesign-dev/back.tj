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
                        <a href="<?= base_url('blog') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('blog/edit_blog/' . $blog['id'], [], ['id' => $blog['id']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <img src="<?= base_url('https://terasjapan.com/ImageTerasJapan/news_event/') . $blog['image'] ?>" 
                                     alt="News Image" class="img-thumbnail" style="max-width: 150px;">
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
                    <label class="col-md-4 text-md-right" for="title">Judul</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" 
                               value="<?= set_value('title', $blog['title']); ?>" 
                               class="form-control" placeholder="Masukkan Judul">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="captions">Caption</label>
                    <div class="col-md-6">
                        <input type="text" id="captions" name="captions" 
                               value="<?= set_value('captions', $blog['captions']); ?>" 
                               class="form-control" placeholder="Masukkan Caption">
                        <?= form_error('captions', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="description" name="description" class="form-control" 
                                  rows="4" placeholder="Masukkan Deskripsi"><?= set_value('description', $blog['description']); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Update</span>
                        </button>
                        <button type="reset" class="btn btn-secondary btn-icon-split">
                            <span class="icon"><i class="fas fa-undo"></i></span>
                            <span class="text">Reset</span>
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
