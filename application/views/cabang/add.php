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
                    <label class="col-md-4 text-md-right" for="branch_code">Kode Cabang</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('branch_code'); ?>" type="text" id="branch_code" name="branch_code" class="form-control" placeholder="Masukkan Kode Cabang">
                        <?= form_error('branch_code', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="branch_name">Nama Cabang</label>
                    <div class="col-md-6">
                        <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Nama Cabang">
                        <?= form_error('branch_name', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="address">Alamat</label>
                    <div class="col-md-6">
                        <textarea name="address" id="address" cols="30" rows="10" class="form-control"></textarea>
                        <?= form_error('address', '<span class="text-danger small">', '</span>'); ?>
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
