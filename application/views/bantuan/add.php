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
                        <a href="<?= base_url('bantuan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('bantuan/tambah_save'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="judul">Judul</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('judul'); ?>" type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan Judul">
                        <?= form_error('judul', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="isi">Isi</label>
                    <div class="col-md-12">
                        <textarea name="isi" id="isi" class="form-control" rows="5"></textarea>
                        <?= form_error('namacabang', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tags">Kategori</label>
                    <div class="col-md-6">
                        <select name="tags" id="tags" class="form-control">
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            <option value="membership">Membership</option>
                            <option value="voucher">Voucher</option>
                            <option value="poin">Poin</option>
                            <option value="topup">Top Up</option>
                        </select>
                        <?= form_error('alamat', '<span class="text-danger small">', '</span>'); ?>
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