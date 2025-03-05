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
                <?php echo form_open_multipart('transaksi/cari_member_kasir', array('id' => 'memberForm')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Handphone</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('nomor'); ?>" type="text" id="nomor" name="nomor" class="form-control" placeholder="Masukkan No Handphone">
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>

                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fas fa-search"></i></span>
                            <span class="text">Cari</span>
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
        var nohpInput = document.getElementById('nomor');
        var errorMessage = document.getElementById('error-message');

        memberForm.addEventListener("submit", function (event) {
            if (!nohpInput.value.trim()) {
                event.preventDefault();
                errorMessage.innerText = "Nomor Handphone tidak boleh kosong";
                errorMessage.style.display = "block";
                nohpInput.classList.add("is-invalid");
            } else {
                errorMessage.style.display = "none";
                nohpInput.classList.remove("is-invalid");
            }
        });

        nohpInput.addEventListener("input", function () {
            errorMessage.style.display = "none";
            nohpInput.classList.remove("is-invalid");
        });
    });
</script>

