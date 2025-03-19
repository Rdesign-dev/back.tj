<?php

?>

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
                        <a href="<?= base_url('transaksi') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('transaksi/convert_and_update', 'id="memberForm"'); ?>

                <!-- Hapus atau comment bagian ini
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggaltransaksi">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('tanggaltransaksi'); ?>" type="datetime-local" id="tanggaltransaksi"
                            name="tanggaltransaksi" class="form-control" placeholder="tanggaltransaksi">
                        <span class="text-danger small"><?= form_error('tanggaltransaksi'); ?></span>
                    </div>
                </div>
                -->

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nocabang">Nama Cabang</label>
                    <div class="col-md-6">
                        <select name="nocabang" id="nocabang" class="form-control">
                            <option value="" selected disabled>Pilih Cabang</option>
                            <?php foreach ($cabang as $cbg): ?>
                            <option value="<?= $cbg['id'] ?>">
                                <?= $cbg['branch_code'] ?> | <?= $cbg['branch_name'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger small"><?= form_error('nocabang'); ?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Member</label>
                    <div class="col-md-6">
                        <?php if(isset($member) && !empty($member)): ?>
                        <?php foreach($member as $mb => $data): ?>
                        <input type="text" class="form-control" name="nomor" id="nomor"
                            value="<?= isset($data['phone_number']) ? $data['phone_number'] : set_value('nomor', ''); ?>"
                            readonly>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <span class="text-danger small"><?= form_error('nomor'); ?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <?php if(isset($member) && !empty($member)): ?>
                        <?php foreach($member as $mb => $data): ?>
                        <input type="text" class="form-control" name="nama" id="nama"
                            value="<?= isset($data['name']) ? $data['name'] : ''; ?>" readonly>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <span class="text-danger small"><?= form_error('nama'); ?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="saldo">Saldo Member</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="saldo" 
                               value="<?= isset($saldo) ? (int)$saldo : '0' ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="penukaranvoucher">Penukaran Voucher</label>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <input type="checkbox" id="tukarVoucher" name="tukarVoucher" value="1">
                                <span> Ya</span>
                            </div>
                            <div class="flex-grow-1" id="divKodevoucher" style="display: none;">
                                <select name="kodevouchertukar" id="kodevouchertukar" class="form-control">
                                    <option value="" selected>Pilih Voucher</option>
                                    <?php foreach ($unused_vouchers as $voucher): ?>
                                    <option value="<?= $voucher['kode_voucher'] ?>"><?= $voucher['kode_voucher'] ?>
                                        (Points: <?= $voucher['points_used'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?= form_error('tukarVoucher', '<span class="text-danger small">', '</span>'); ?>
                        <?= form_error('kodevouchertukar', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div id="nonVoucherFields">
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="total">Total</label>
                        <div class="col-md-6">
                            <input value="<?= set_value('total'); ?>" type="number" id="total" name="total"
                                class="form-control" placeholder="Masukkan Total" min="0">
                            <span class="text-danger small"><?= form_error('total'); ?></span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Split Bill</label>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="splitBill" name="splitBill">
                                <label class="custom-control-label" for="splitBill">Ya, gunakan split bill</label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="primary_payment_method">
                            <span id="payment_method_label">Metode Pembayaran</span>
                        </label>
                        <div class="col-md-6">
                            <select name="primary_payment_method" id="primary_payment_method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transferBank">Transfer Bank</option>
                                <option value="Balance">Saldo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Payment Amount -->
                    <div class="row form-group" id="primary_amount_section" style="display: none;">
                        <label class="col-md-4 text-md-right" for="primary_amount">
                            <span id="payment_amount_label">Jumlah Pembayaran Utama</span>
                        </label>
                        <div class="col-md-6">
                            <input type="number" id="primary_amount" name="primary_amount" class="form-control" placeholder="Masukkan jumlah" min="0">
                            <span class="text-danger small" id="primary_amount_error"></span>
                        </div>
                    </div>

                    <!-- Secondary Payment Section -->
                    <div id="secondary_payment_section" style="display:none;">
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right">Sisa yang Harus Dibayar</label>
                            <div class="col-md-6">
                                <input type="text" id="remaining_amount" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="secondary_payment_method">Metode Pembayaran Kedua</label>
                            <div class="col-md-6">
                                <select name="secondary_payment_method" id="secondary_payment_method" class="form-control">
                                    <option value="cash">Cash</option>
                                    <option value="transferBank">Transfer Bank</option>
                                    <option value="Balance">Saldo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="fotobill">Foto Bill</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('fotobill'); ?>" type="file" id="fotobill" name="fotobill"
                            class="form-control">
                        <span class="text-danger small"><?= form_error('fotobill'); ?></span>
                        <span class="text-danger small">*Foto Maks.2Mb</span>
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
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Element selections
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const divKodevoucher = document.getElementById('divKodevoucher');
    const nonVoucherFields = document.getElementById('nonVoucherFields');
    const memberForm = document.getElementById('memberForm');
    const totalInput = document.getElementById('total');
    const fotobillInput = document.getElementById('fotobill');
    const splitBillCheckbox = document.getElementById('splitBill');
    const primaryAmountInput = document.getElementById('primary_amount');
    const remainingAmountInput = document.getElementById('remaining_amount');
    const secondaryPaymentSection = document.getElementById('secondary_payment_section');
    const primaryPaymentMethod = document.getElementById('primary_payment_method');
    const secondaryPaymentMethod = document.getElementById('secondary_payment_method');
    const paymentMethodLabel = document.getElementById('payment_method_label');
    const paymentAmountLabel = document.getElementById('payment_amount_label');
    const primaryAmountSection = document.getElementById('primary_amount_section');

    // Function to toggle voucher fields
    function toggleVoucherFields(isVoucherMode) {
        // Hanya toggle dropdown voucher, tidak mempengaruhi field lain
        divKodevoucher.style.display = isVoucherMode ? 'block' : 'none';
    }

    // Calculate remaining amount for split bill
    function calculateRemaining() {
        const total = parseInt(document.getElementById('total').value) || 0;
        const primaryAmount = parseInt(document.getElementById('primary_amount').value) || 0;
        
        if (splitBillCheckbox.checked && total > 0) {
            const remaining = total - primaryAmount;
            remainingAmountInput.value = remaining > 0 ? remaining : 0; // Ensure no decimals
            
            // Validate primary amount
            if (primaryAmount >= total) {
                primaryAmountInput.classList.add('is-invalid');
                document.getElementById('primary_amount_error').textContent = 
                    'Jumlah pembayaran utama harus lebih kecil dari total';
            } else {
                primaryAmountInput.classList.remove('is-invalid');
                document.getElementById('primary_amount_error').textContent = '';
            }
        }
    }

    // Event Listeners
    tukarVoucherCheckbox.addEventListener('change', () => toggleVoucherFields(tukarVoucherCheckbox.checked));
    
    splitBillCheckbox.addEventListener('change', function() {
        // Update labels based on split bill checkbox
        paymentMethodLabel.textContent = this.checked ? 'Metode Pembayaran Utama' : 'Metode Pembayaran';
        paymentAmountLabel.textContent = this.checked ? 'Jumlah Pembayaran Utama' : 'Jumlah Pembayaran';
        
        if (this.checked) {
            // Show secondary payment section immediately when split bill is checked
            primaryAmountSection.style.display = 'block';
            secondaryPaymentSection.style.display = 'block';
            // Reset values
            primaryAmountInput.value = '';
            remainingAmountInput.value = totalInput.value; // Set initial remaining amount to total
            // Enable secondary payment method
            secondaryPaymentMethod.disabled = false;
            calculateRemaining();
        } else {
            primaryAmountSection.style.display = 'none';
            secondaryPaymentSection.style.display = 'none';
            remainingAmountInput.value = '';
        }
    });

    totalInput.addEventListener('input', calculateRemaining);
    primaryAmountInput.addEventListener('input', calculateRemaining);

    // Handle payment method changes
    primaryPaymentMethod.addEventListener('change', function() {
        const selectedMethod = this.value;
        secondaryPaymentMethod.value = '';
        Array.from(secondaryPaymentMethod.options).forEach(option => {
            option.disabled = option.value === selectedMethod;
        });
        calculateRemaining();
    });

    // Form validation
    memberForm.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate total for all transactions
        if (!totalInput.value.trim()) {
            totalInput.classList.add('is-invalid');
            isValid = false;
        }

        // Validate voucher if selected
        if (tukarVoucherCheckbox.checked && !document.getElementById('kodevouchertukar').value) {
            document.getElementById('kodevouchertukar').classList.add('is-invalid');
            isValid = false;
        }

        // Validate split bill if enabled
        if (splitBillCheckbox.checked) {
            const total = parseFloat(totalInput.value) || 0;
            const primaryAmount = parseFloat(primaryAmountInput.value) || 0;
            
            if (primaryAmount >= total || primaryAmount <= 0 || !secondaryPaymentMethod.value) {
                isValid = false;
                alert('Pastikan:\n- Jumlah pembayaran utama lebih kecil dari total\n- Metode pembayaran kedua sudah dipilih');
            }
        }

        // Validate foto bill
        if (!fotobillInput.value.trim()) {
            fotobillInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Remove invalid class on input
    [totalInput, fotobillInput, document.getElementById('kodevouchertukar')].forEach(element => {
        element?.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Initialize
    toggleVoucherFields(tukarVoucherCheckbox.checked);
});
</script>
