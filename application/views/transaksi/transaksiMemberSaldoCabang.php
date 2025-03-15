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
                        <!-- Updated back button URL -->
                        <a href="<?= base_url('transaksicabang/saldoCabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <!-- Updated form action URL -->
                <?php echo form_open_multipart('transaksicabang/convert_and_updateSaldoCabang'); ?>
                
                <!-- Member info first -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nomor" id="nomor" value="<?= $member->phone_number ?? '' ?>" class="form-control" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nama" id="nama" value="<?= $member->name ?? '' ?>" class="form-control" readonly>
                    </div>
                </div>

                <!-- Top up form -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nominal">Nominal TopUp</label>
                    <div class="col-md-6">
                        <input type="text" id="nominal" name="nominal" class="form-control" placeholder="Minimal Rp 10.000">
                        <?= form_error('nominal', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="metode">Metode Pembayaran</label>
                    <div class="col-md-6">
                        <select name="metode" id="metode" class="form-control">
                            <option value="">- Pilih Metode Pembayaran -</option>
                            <option value="cash">Cash</option>
                            <option value="transferBank">Transfer Bank</option>
                        </select>
                        <?= form_error('metode', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group" id="buktiDiv" style="display:none;">
                    <label class="col-md-4 text-md-right" for="bukti">Bukti Pembayaran</label>
                    <div class="col-md-6">
                        <input type="file" id="bukti" name="bukti" class="form-control">
                        <?= form_error('bukti', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <br>
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
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const metodeSelect = document.getElementById('metode');
    const buktiDiv = document.getElementById('buktiDiv');
    const nominalInput = document.getElementById('nominal');
    const form = document.querySelector('form');

    // Show/hide bukti upload based on payment method
    metodeSelect.addEventListener('change', function() {
        buktiDiv.style.display = this.value === 'transferBank' ? 'flex' : 'none';
    });

    // Format nominal with thousand separator
    nominalInput.addEventListener('input', function(e) {
        let value = this.value.replace(/[^\d]/g, '');
        if (value === '') return;
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });

    // Before form submit, remove formatting
    form.addEventListener('submit', function(e) {
        let cleanValue = nominalInput.value.replace(/[^\d]/g, '');
        nominalInput.value = cleanValue;
    });
});
</script>