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
                        <a href="<?= base_url('member') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('member/tambah_save', array('id' => 'memberForm')); ?>
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
                        <input value="<?= set_value('nomor'); ?>" type="text" id="nomor" name="nomor" class="form-control" pattern="[0-9]+" title="Hanya boleh diisi oleh angka, dan diawali dengan 08" placeholder="Nomor Handphone Cth:08xxx">
                        <?= form_error('nomor', '<span class="text-danger small">', '</span>'); ?>

                    </div>
                </div>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary btn-icon-split">
                            <span class="icon"><i class="fas fa-backspace"></i></span>
                            <span class="text">Reset</span>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
                
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var memberForm = document.getElementById('memberForm');

        memberForm.addEventListener("submit", function (event) {
            var namamemberInput = document.getElementById('namamember');
            var nomorInput = document.getElementById('nomor');

            if (!namamemberInput.value.trim()) {
                event.preventDefault();
                namamemberInput.classList.add("is-invalid");
            } else {
                namamemberInput.classList.remove("is-invalid");
            }

            if (!nomorInput.value.trim()) {
                event.preventDefault();
                nomorInput.classList.add("is-invalid");
            } else {
                nomorInput.classList.remove("is-invalid");
            }
        });
    });
</script>
