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
                <?= form_open_multipart('transaksi/cari_member', array('id' => 'memberForm')); ?>
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
                        <button type="submit" class="btn btn-primary btn-icon-split" id="btn-submit">
                            <span class="icon"><i class="fas fa-search"></i></span>
                            <span class="text">Cari</span>
                        </button>
                        <button type="reset" class="btn btn-secondary btn-icon-split">
                            <span class="icon"><i class="fas fa-backspace"></i></span>
                            <span class="text">Reset</span>
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
        var nomorInput = document.getElementById('nomor');
        var errorMessage = document.getElementById('error-message');

        memberForm.addEventListener("submit", function (event) {
            if (!nomorInput.value.trim()) {
                event.preventDefault();
                nomorInput.classList.add("is-invalid");
                errorMessage.innerHTML = "Nomor Handphone tidak boleh kosong.";
            } else {
                nomorInput.classList.remove("is-invalid");
                errorMessage.innerHTML = "";
            }
        });

        nomorInput.addEventListener("input", function () {
            nomorInput.classList.remove("is-invalid");
            errorMessage.innerHTML = "";
        });
    });
</script>
