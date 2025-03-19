<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Transaksi Member
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('transaksikasir/convert_and_updateKasir') ?>" method="post" enctype="multipart/form-data" id="transactionForm">
                    
                    <!-- Member Information -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Nomor HP</label>
                        <div class="col-md-6">
                            <input type="text" name="nomor" class="form-control" value="<?= $member->phone_number ?? ''; ?>" readonly>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Nama Member</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="<?= $member->name ?? ''; ?>" readonly>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Saldo</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="Rp <?= number_format($member->balance ?? 0, 0, ',', '.'); ?>" readonly>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Poin</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="<?= number_format($member->poin ?? 0, 0, ',', '.'); ?>" readonly>
                        </div>
                    </div>

                    <!-- Basic Transaction Info -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="total">Total Transaksi</label>
                        <div class="col-md-6">
                            <input type="number" id="total" name="total" class="form-control" 
                                   min="1000" placeholder="Masukkan total transaksi" required>
                        </div>
                    </div>

                    <!-- Voucher Section -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Penukaran Voucher</label>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="tukarVoucher" name="tukarVoucher">
                                <label class="custom-control-label" for="tukarVoucher">Ya, gunakan voucher</label>
                            </div>
                            <div id="divKodevoucher" style="display:none;" class="mt-2">
                                <select name="kodevouchertukar" id="kodevouchertukar" class="form-control">
                                    <option value="">Pilih Voucher</option>
                                    <?php foreach ($unused_vouchers as $voucher): ?>
                                        <option value="<?= $voucher['redeem_id'] ?>">
                                            <?= $voucher['kode_voucher'] ?> (Points: <?= $voucher['points_used'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Split Bill Option -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Split Bill</label>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="splitBill" name="splitBill">
                                <label class="custom-control-label" for="splitBill">Ya, gunakan split bill</label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Metode Pembayaran</label>
                        <div class="col-md-6">
                            <select name="primary_payment_method" id="primary_payment_method" class="form-control" required>
                                <option value="">Pilih Metode</option>
                                <option value="cash">Cash</option>
                                <option value="transferBank">Transfer Bank</option>
                                <option value="Balance">Saldo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Split Bill Fields -->
                    <div id="splitBillFields" style="display:none;">
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Metode Pembayaran Kedua</label>
                            <div class="col-md-6">
                                <select name="secondary_payment_method" id="secondary_payment_method" class="form-control">
                                    <option value="">Pilih Metode</option>
                                    <option value="cash">Cash</option>
                                    <option value="transferBank">Transfer Bank</option>
                                    <option value="Balance">Saldo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Jumlah Pembayaran Pertama</label>
                            <div class="col-md-6">
                                <input type="number" id="primary_amount" name="primary_amount" class="form-control" placeholder="Masukkan jumlah pembayaran pertama">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Sisa Pembayaran</label>
                            <div class="col-md-6">
                                <input type="number" id="remaining_amount" name="remaining_amount" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Foto Bill</label>
                        <div class="col-md-6">
                            <input type="file" name="fotobill" id="fotobill" class="form-control" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col offset-md-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const splitBillCheckbox = document.getElementById('splitBill');
    const splitBillFields = document.getElementById('splitBillFields');
    const primaryPaymentMethod = document.getElementById('primary_payment_method');
    const secondaryPaymentMethod = document.getElementById('secondary_payment_method');
    const primaryAmount = document.getElementById('primary_amount');
    const remainingAmount = document.getElementById('remaining_amount');
    const totalInput = document.getElementById('total');
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const kodevouchertukar = document.getElementById('kodevouchertukar');
    const divKodevoucher = document.getElementById('divKodevoucher');
    const memberBalance = parseFloat('<?= $member->balance ?? 0 ?>');
    const transactionForm = document.getElementById('transactionForm');

    // Toggle voucher fields
    tukarVoucherCheckbox.addEventListener('change', function() {
        divKodevoucher.style.display = this.checked ? 'block' : 'none';
        kodevouchertukar.disabled = !this.checked;
        kodevouchertukar.required = this.checked;
    });

    // Toggle split bill fields
    splitBillCheckbox.addEventListener('change', function() {
        splitBillFields.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            primaryAmount.value = '';
            remainingAmount.value = '';
            secondaryPaymentMethod.value = '';
        }
    });

    // Update payment methods based on primary selection
    primaryPaymentMethod.addEventListener('change', function() {
        const selectedMethod = this.value;
        const total = parseInt(totalInput.value) || 0;
        
        if (selectedMethod === 'Balance') {
            if (total > memberBalance) {
                alert('Saldo tidak mencukupi untuk melakukan pembayaran');
                this.value = '';
                return;
            }
        }
        
        // Reset secondary payment options
        if (secondaryPaymentMethod) {
            secondaryPaymentMethod.innerHTML = `
                <option value="">Pilih Metode</option>
                <option value="cash">Cash</option>
                <option value="transferBank">Transfer Bank</option>
                ${selectedMethod !== 'Balance' ? '<option value="Balance">Saldo</option>' : ''}
            `;
        }
    });

    // Calculate remaining amount for split bill
    if (primaryAmount) {
        primaryAmount.addEventListener('input', function() {
            const total = parseInt(totalInput.value) || 0;
            const primary = parseInt(this.value) || 0;
            
            if (primary >= total) {
                this.value = total - 1000;
                remainingAmount.value = 1000;
            } else {
                remainingAmount.value = total - primary;
            }
        });
    }

    // Form validation
    transactionForm.addEventListener('submit', function(e) {
        const total = parseInt(totalInput.value) || 0;
        
        if (total < 1000) {
            e.preventDefault();
            alert('Total transaksi minimal Rp 1.000');
            return;
        }

        if (splitBillCheckbox.checked) {
            const primaryAmt = parseInt(primaryAmount.value) || 0;
            const remainingAmt = parseInt(remainingAmount.value) || 0;
            
            if (!primaryAmt || !secondaryPaymentMethod.value) {
                e.preventDefault();
                alert('Lengkapi data split bill');
                return;
            }

            if (primaryAmt + remainingAmt !== total) {
                e.preventDefault();
                alert('Total pembayaran split bill tidak sesuai');
                return;
            }
        }

        // Validate voucher if used
        if (tukarVoucherCheckbox.checked && !kodevouchertukar.value) {
            e.preventDefault();
            alert('Pilih voucher yang akan digunakan');
            return;
        }
    });
});
</script>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>