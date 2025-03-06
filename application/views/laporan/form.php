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
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open(); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('tanggal'); ?>" type="text" id="tanggal" name="tanggal" class="form-control">
                        <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nocabang">Nama Cabang</label>
                    <div class="col-md-6">
                        <select name="nocabang" id="nocabang" class="form-control">
                            <option value="" disabled selected>-- Pilih Cabang --</option>
                            <option value="all">Semua Cabang</option>
                            <?php foreach ($cabang as $cbg) : ?>
                                <option value="<?= $cbg['id'] ?>"><?= $cbg['branch_code'] ?> | <?= $cbg['branch_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('nocabang', '<span class="text-danger small">', '</span>'); ?>
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