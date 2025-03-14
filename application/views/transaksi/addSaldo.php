<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Top Up Saldo
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('transaksi/getHistorysaldo') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('transaksi/cari_memberSaldo', array('id' => 'memberForm')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nohp">Nomor Handphone</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input value="<?= set_value('nohp'); ?>" type="text" id="nohp" name="nohp" class="form-control" placeholder="Masukkan No Handphone">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const memberForm = document.getElementById('memberForm');
    const nohpInput = document.getElementById('nohp');
    const errorMessage = document.getElementById('error-message');

    memberForm.addEventListener("submit", function (event) {
        if (!nohpInput.value.trim()) {
            event.preventDefault();
            errorMessage.innerText = "Nomor Handphone tidak boleh kosong";
            errorMessage.style.display = "block";
            nohpInput.classList.add("is-invalid");
        } else if (!/^\d+$/.test(nohpInput.value.trim())) {
            event.preventDefault();
            errorMessage.innerText = "Nomor Handphone harus berupa angka";
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
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>

