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
                        <a href="<?= base_url('transaksicabang/tambahTransaksiCabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('transaksicabang/convert_and_updateCabang', ['id' => 'transactionForm']); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Nomor Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nomor" class="form-control" value="<?= $member->phone_number ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Nama Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nama" class="form-control" value="<?= $member->name ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Balance</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" value="Rp <?= number_format($member->balance, 0, ',', '.') ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Poin</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" value="<?= number_format($member->poin) ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Kode Voucher</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" id="tukarVoucher" name="tukarVoucher">
                                </div>
                            </div>
                            <select name="kodevouchertukar" id="kodevouchertukar" class="form-control" disabled>
                                <option value="" selected>Pilih Voucher</option>
                                <?php foreach ($unused_vouchers as $voucher): ?>
                                    <option value="<?= $voucher->redeem_id ?>"><?= $voucher->kode_voucher ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?= form_error('kodevouchertukar', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="payment-field">
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Total</label>
                        <div class="col-md-6">
                            <input type="number" name="total" id="total" class="form-control" min="1000">
                            <small class="text-muted">Minimal transaksi Rp 1.000</small>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Metode Pembayaran</label>
                        <div class="col-md-6">
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="" selected>Pilih Metode</option>
                                <option value="CSH">Cash</option>
                                <option value="TFB">Transfer Bank</option>
                                <option value="BM">Balance Member</option>
                            </select>
                            <?= form_error('payment_method', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input type="datetime-local" name="tanggaltransaksi" id="tanggaltransaksi" class="form-control" required>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Upload Bukti</label>
                    <div class="col-md-6">
                        <input type="file" name="fotobill" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG/PNG/JPEG (Max: 2MB)</small>
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const kodevouchertukar = document.getElementById('kodevouchertukar');
    const paymentFields = document.querySelectorAll('.payment-field');
    const payment_method = document.getElementById('payment_method');
    const total = document.getElementById('total');

    // Set default datetime
    document.getElementById('tanggaltransaksi').value = new Date().toISOString().slice(0, 16);

    tukarVoucherCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        
        // Toggle payment fields visibility
        paymentFields.forEach(field => {
            field.style.display = isChecked ? 'none' : 'block';
        });

        // Toggle voucher select enabled state
        kodevouchertukar.disabled = !isChecked;

        // Toggle required fields
        payment_method.required = !isChecked;
        total.required = !isChecked;
        kodevouchertukar.required = isChecked;

        // Reset values when toggling
        if (!isChecked) {
            kodevouchertukar.value = '';
        } else {
            payment_method.value = '';
            total.value = '';
        }
    });

    // Form validation
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        if (!tukarVoucherCheckbox.checked) {
            const amount = parseInt(total.value);
            if (amount < 1000) {
                e.preventDefault();
                alert('Minimal transaksi Rp 1.000');
                return;
            }

            if (payment_method.value === 'BM') {
                const balance = <?= $member->balance ?>;
                if (balance < amount) {
                    e.preventDefault();
                    alert('Saldo member tidak mencukupi');
                    return;
                }
            }
        }
    });
});
</script>