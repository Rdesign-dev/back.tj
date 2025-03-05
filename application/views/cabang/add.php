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
                        <a href="<?= base_url('cabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('cabang/tambah_save', array('id' => 'memberForm')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="kodecabang">Kode Cabang</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('kodecabang'); ?>" type="text" id="kodecabang" name="kodecabang" class="form-control" placeholder="Masukkan Kode Cabang">
                        <?= form_error('kodecabang', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="namacabang">Nama Cabang</label>
                    <div class="col-md-6">
                        <input type="text" id="namacabang" name="namacabang" class="form-control" placeholder="namacabang">
                        <?= form_error('namacabang', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Alamat</label>
                    <div class="col-md-6">
                        <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control"></textarea>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var memberForm = document.getElementById('memberForm');

        memberForm.addEventListener("submit", function (event) {
            var kodecabangInput = document.getElementById('kodecabang');
            var namacabangInput = document.getElementById('namacabang');
            var alamatInput = document.getElementById('alamat');

            if (!kodecabangInput.value.trim()) {
                event.preventDefault();
                kodecabangInput.classList.add("is-invalid");
            } else {
                kodecabangInput.classList.remove("is-invalid");
            }

            if (!namacabangInput.value.trim()) {
                event.preventDefault();
                namacabangInput.classList.add("is-invalid");
            } else {
                namacabangInput.classList.remove("is-invalid");
            }

            if (!alamatInput.value.trim()) {
                event.preventDefault();
                alamatInput.classList.add("is-invalid");
            } else {
                alamatInput.classList.remove("is-invalid");
            }
        });
    });
</script>
