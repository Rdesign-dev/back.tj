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
                        <a href="<?= base_url('member/indexCabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('member/tambah_saveCabang'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="namamember">Nama Member</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('namamember'); ?>" type="text" id="namamember" name="namamember" class="form-control" placeholder="Masukkan Nama Member">
                        <?= form_error('namamember', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Handphone</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('nomor'); ?>" type="text" id="nomor" name="nomor" class="form-control" placeholder="Masukkan Nomor Handphone">
                        <?= form_error('nomor', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ambil elemen input yang belum diisi
        var emptyInput = document.querySelector('input[value=""]');
        
        if (emptyInput) {
            // Fokuskan input yang belum diisi
            emptyInput.focus();
        }
    });
</script>
