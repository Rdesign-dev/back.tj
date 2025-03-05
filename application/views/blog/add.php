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
                <?php echo form_open_multipart('blog/tambah_save'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="gambar">Gambar</label>
                    <div class="col-md-6">
                        <input type="file" id="gambar" name="gambar" class="form-control" placeholder="gambar">
                        <?= form_error('gambar', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="judul">Judul Blog</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('judul'); ?>" type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan Kode Voucher">
                        <?= form_error('judul', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="konten">Konten Blog</label>
                    <div class="col-md-12">
                        <textarea name="konten" id="kontenBlog" class="form-control"></textarea>
                        <?= form_error('konten', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
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