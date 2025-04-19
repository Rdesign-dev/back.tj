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
                        <a href="<?= base_url('transaksi/saldo') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('transaksi/convert_and_updateSaldoMember'); ?>

                <!-- Member Info Display -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nomor" id="nomor" class="form-control" 
                            value="<?= isset($member->phone_number) ? $member->phone_number : '' ?>" readonly>
                        <?= form_error('nomor', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nama" id="nama" class="form-control" 
                            value="<?= isset($member->name) ? $member->name : '' ?>" readonly>
                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <!-- Top Up Form -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nominal">Nominal TopUp</label>
                    <div class="col-md-6">
                        <input type="text" id="nominal" name="nominal" class="form-control"
                               placeholder="Minimal Rp 10.000" required autocomplete="off">
                        <?= form_error('nominal', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="metode">Metode Pembayaran</label>
                    <div class="col-md-6">
                        <select name="metode" id="metode" class="form-control" required>
                            <option value="">Pilih Metode</option>
                            <option value="cash">Cash</option>
                            <option value="transferBank">Transfer Bank</option>
                        </select>
                        <?= form_error('metode', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="bukti">Bukti Pembayaran</label>
                    <div class="col-md-6">
                        <input type="file" id="bukti" name="bukti" class="form-control"
                               accept="image/*" required>
                        <small class="text-muted">Max size: 10MB. Format: JPG, JPEG, PNG</small>
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
    // Format nominal input with dot every 3 digits
    const nominalInput = document.getElementById('nominal');
    nominalInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value === '') {
            this.value = '';
            return;
        }
        this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    // Remove formatting before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        nominalInput.value = nominalInput.value.replace(/\D/g, '');
    });

    // JS validation for required fields
    this.querySelector('form').addEventListener("submit", function(event) {
        ['nominal','metode','bukti','nomor','nama'].forEach(function(id){
            var el = document.getElementById(id);
            if (el && !el.value.trim()) {
                event.preventDefault();
                el.classList.add("is-invalid");
            } else if(el) {
                el.classList.remove("is-invalid");
            }
        });
    });

    document.getElementById('metode').addEventListener('change', function() {
        const buktiTransfer = document.getElementById('buktiTransfer');
        buktiTransfer.style.display = this.value === 'transferBank' ? 'block' : 'none';
        
        const buktiInput = document.getElementById('bukti');
        buktiInput.required = this.value === 'transferBank';
    });
});
</script>