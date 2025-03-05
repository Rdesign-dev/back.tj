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
                        <a href="<?= base_url('undian/inputPoinUndian') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('',[], ['id' => $undian['id']]); ?>
                <!-- Tambahkan input hidden untuk menyimpan ID member -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="poin">Poin Promosi</label>
                    <div class="col-md-6">
                    <?php if ($undian) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <input type="text" id="poin" name="poin" value="<?= set_value('poin', $undian['poin']); ?>" class="form-control" placeholder="Masukkan poin Promosi">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="foto">Foto Undian</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                            <?php if ($undian) : ?>
                                <img src="<?= base_url() . '../fotoundian/' . $undian['gambar'] ?>" alt="User" class="rounded-circle shadow-sm img-thumbnail">
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <input type="file" name="gambar" id="gambar">
                                <?= form_error('gambar', '<small class="text-danger">', '</small>'); ?>
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
                <?php endif; ?>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
