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
                                        <option value="<?= $voucher['kode_voucher'] ?>">
                                            <?= $voucher['kode_voucher'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div id="paymentSection">
                        <!-- Split Bill Checkbox -->
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Split Bill</label>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="splitBill" name="splitBill">
                                    <label class="custom-control-label" for="splitBill">Ya, gunakan split bill</label>
                                </div>
                            </div>
                        </div>

                        <!-- Primary Payment -->
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Metode Pembayaran</label>
                            <div class="col-md-6">
                                <select name="primary_payment_method" id="primary_payment_method" class="form-control mb-2">
                                    <option value="">Pilih Metode</option>
                                    <option value="cash">Cash</option>
                                    <option value="transferBank">Transfer Bank</option>
                                    <option value="Balance">Saldo</option>
                                </select>
                                <input type="text" id="primary_amount" name="primary_amount_display" class="form-control" 
                                       placeholder="Masukkan jumlah pembayaran" autocomplete="off">
                            </div>
                        </div>

                        <!-- Secondary Payment (Hidden by default) -->
                        <div id="secondary_payment_section" style="display:none;">
                            <div class="row form-group">
                                <label class="col-md-4 text-md-right">Metode Pembayaran Kedua</label>
                                <div class="col-md-6">
                                    <select name="secondary_payment_method" id="secondary_payment_method" class="form-control mb-2">
                                        <option value="">Pilih Metode</option>
                                        <option value="cash">Cash</option>
                                        <option value="transferBank">Transfer Bank</option>
                                        <option value="Balance">Saldo</option>
                                    </select>
                                    <input type="text" id="secondary_amount" name="secondary_amount_display" class="form-control" 
                                           placeholder="Masukkan jumlah pembayaran kedua" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Total</label>
                            <div class="col-md-6">
                                <input type="text" id="total" name="total_display" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Bill Photo -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Foto Bill</label>
                        <div class="col-md-6">
                            <input type="file" name="fotobill" id="fotobill" class="form-control" required>
                            <small class="text-danger">*Foto Maks.2Mb</small>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row form-group justify-content-end">
                        <div class="col-md-8">
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
    const primaryAmountInput = document.getElementById('primary_amount');
    const secondaryAmountInput = document.getElementById('secondary_amount');
    const totalInput = document.getElementById('total');
    const secondaryPaymentSection = document.getElementById('secondary_payment_section');
    const primaryPaymentMethod = document.getElementById('primary_payment_method');
    const secondaryPaymentMethod = document.getElementById('secondary_payment_method');
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const divKodevoucher = document.getElementById('divKodevoucher');

    function formatRupiah(number) {
        return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseRupiah(rupiahString) {
        return parseInt(rupiahString.replace(/[^\d]/g, '')) || 0;
    }

    function calculateTotal() {
        const amount1 = parseRupiah(primaryAmountInput.value) || 0;
        const amount2 = parseRupiah(secondaryAmountInput.value) || 0;
        const total = amount1 + amount2;
        
        totalInput.value = formatRupiah(total);
        
        let hiddenTotal = document.getElementById('total_hidden');
        if (!hiddenTotal) {
            hiddenTotal = document.createElement('input');
            hiddenTotal.type = 'hidden';
            hiddenTotal.name = 'total';
            hiddenTotal.id = 'total_hidden';
            totalInput.parentNode.insertBefore(hiddenTotal, totalInput.nextSibling);
        }
        hiddenTotal.value = total;
    }

    // Handle amount inputs
    [primaryAmountInput, secondaryAmountInput].forEach(input => {
        input.addEventListener('input', function() {
            this.value = formatRupiah(this.value.replace(/[^\d]/g, ''));
            calculateTotal();
        });
    });

    // Toggle split bill
    splitBillCheckbox.addEventListener('change', function() {
        secondaryPaymentSection.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            secondaryAmountInput.value = '';
            calculateTotal();
        }
    });

    // Prevent same payment method
    primaryPaymentMethod.addEventListener('change', function() {
        const selectedMethod = this.value;
        Array.from(secondaryPaymentMethod.options).forEach(option => {
            option.disabled = option.value === selectedMethod;
        });
    });

    // Toggle voucher section
    tukarVoucherCheckbox.addEventListener('change', function() {
        divKodevoucher.style.display = this.checked ? 'block' : 'none';
    });
});
</script>

