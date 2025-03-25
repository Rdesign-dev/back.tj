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

                <!-- Branch Selection -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nocabang">Nama Cabang</label>
                    <div class="col-md-6">
                        <select name="nocabang" id="nocabang" class="form-control">
                            <option value="" selected disabled>Pilih Cabang</option>
                            <?php foreach ($cabang as $cbg): ?>
                            <option value="<?= $cbg['id'] ?>"><?= $cbg['branch_code'] ?> | <?= $cbg['branch_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('nocabang', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <!-- Member Info -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Member Info</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control mb-2" name="nomor" id="nomor" 
                               value="<?= isset($member[0]['phone_number']) ? $member[0]['phone_number'] : ''; ?>" readonly>
                        <input type="text" class="form-control mb-2" name="nama" id="nama" 
                               value="<?= isset($member[0]['name']) ? $member[0]['name'] : ''; ?>" readonly>
                        <input type="text" class="form-control" id="saldo" 
                               value="Rp <?= isset($member[0]['balance']) ? number_format($member[0]['balance'], 0, ',', '.') : '0' ?>" readonly>
                    </div>
                </div>

                <!-- Voucher Section -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Penukaran Voucher</label>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="tukarVoucher" name="tukarVoucher">
                            <label class="custom-control-label" for="tukarVoucher">Ya, gunakan voucher</label>
                        </div>
                        <select name="kode_voucher" id="kode_voucher" class="form-control" style="display:none;">
                            <option value="">Pilih Voucher</option>
                            <?php foreach ($unused_vouchers as $voucher): ?>
                            <option value="<?= $voucher['kode_voucher'] ?>">
                                <?= $voucher['kode_voucher'] ?> (Points: <?= $voucher['points_used'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
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
                        <label class="col-md-4 text-md-right" for="primary_payment_method">Metode Pembayaran</label>
                        <div class="col-md-6">
                            <select name="primary_payment_method" id="primary_payment_method" class="form-control mb-2">
                                <option value="cash">Cash</option>
                                <option value="transferBank">Transfer Bank</option>
                                <option value="Balance">Saldo</option>
                            </select>
                            <input type="text" 
                                   id="primary_amount" 
                                   name="primary_amount_display" 
                                   class="form-control" 
                                   placeholder="Masukkan jumlah pembayaran"
                                   autocomplete="off">
                        </div>
                    </div>

                    <!-- Secondary Payment -->
                    <div id="secondary_payment_section" style="display:none;">
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="secondary_payment_method">Metode Pembayaran Kedua</label>
                            <div class="col-md-6">
                                <select name="secondary_payment_method" id="secondary_payment_method" class="form-control mb-2">
                                    <option value="cash">Cash</option>
                                    <option value="transferBank">Transfer Bank</option>
                                    <option value="Balance">Saldo</option>
                                </select>
                                <input type="text" 
                                       id="secondary_amount" 
                                       name="secondary_amount_display" 
                                       class="form-control" 
                                       placeholder="Masukkan jumlah pembayaran kedua"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Total</label>
                        <div class="col-md-6">
                            <input type="text" 
                                   id="total" 
                                   name="total_display" 
                                   class="form-control" 
                                   readonly>
                        </div>
                    </div>
                </div>

                <!-- Bill Photo -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="fotobill">Foto Bill</label>
                    <div class="col-md-6">
                        <input type="file" id="fotobill" name="fotobill" class="form-control">
                        <small class="text-danger">*Foto Maks.2Mb</small>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-undo"></i></span>
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
    const splitBillCheckbox = document.getElementById('splitBill');
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const voucherSelect = document.getElementById('kode_voucher');
    const primaryAmountInput = document.getElementById('primary_amount');
    const secondaryAmountInput = document.getElementById('secondary_amount');
    const totalInput = document.getElementById('total');
    const secondaryPaymentSection = document.getElementById('secondary_payment_section');
    const primaryPaymentMethod = document.getElementById('primary_payment_method');
    const secondaryPaymentMethod = document.getElementById('secondary_payment_method');

    // Toggle voucher select
    tukarVoucherCheckbox.addEventListener('change', function() {
        voucherSelect.style.display = this.checked ? 'block' : 'none';
    });

    // Toggle split bill
    splitBillCheckbox.addEventListener('change', function() {
        secondaryPaymentSection.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            secondaryAmountInput.value = '';
            calculateTotal();
        }
    });

    // Format currency function
    function formatRupiah(number) {
        return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Parse currency back to number
    function parseRupiah(rupiahString) {
        return parseInt(rupiahString.replace(/[^\d]/g, '')) || 0;
    }

    // Format display while keeping original value
    function updateAmountDisplay(input) {
        const numericValue = parseFloat(input.value) || 0;
        const formattedValue = formatRupiah(numericValue);
        
        // Create hidden input for form submission
        let hiddenInput = input.nextElementSibling;
        if (!hiddenInput || !hiddenInput.classList.contains('amount-hidden')) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = input.name;
            hiddenInput.className = 'amount-hidden';
            input.parentNode.insertBefore(hiddenInput, input.nextSibling);
            input.name = input.name + '_display';
        }
        
        // Set values
        hiddenInput.value = numericValue;
        input.value = formattedValue;
    }

    // Calculate and display total
    function calculateTotal() {
        const amount1 = parseRupiah(primaryAmountInput.value) || 0;
        const amount2 = parseRupiah(secondaryAmountInput.value) || 0;
        const total = amount1 + amount2;
        
        // Update total display and hidden value
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

    // Handle input events for primary amount
    primaryAmountInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^\d]/g, '');
        updateAmountDisplay(this);
        calculateTotal();
    });

    // Handle input events for secondary amount
    secondaryAmountInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^\d]/g, '');
        updateAmountDisplay(this);
        calculateTotal();
    });

    // Update form validation
    document.getElementById('memberForm').addEventListener('submit', function(e) {
        let isValid = true;

        if (!document.getElementById('nocabang').value) {
            isValid = false;
            alert('Pilih cabang terlebih dahulu');
        }

        const primaryAmount = parseRupiah(primaryAmountInput.value);
        const secondaryAmount = parseRupiah(secondaryAmountInput.value);

        if (splitBillCheckbox.checked) {
            if (!primaryAmount || !secondaryAmount) {
                isValid = false;
                alert('Masukkan jumlah pembayaran untuk kedua metode');
            }
        } else if (!primaryAmount) {
            isValid = false;
            alert('Masukkan jumlah pembayaran');
        }

        if (!document.getElementById('fotobill').value) {
            isValid = false;
            alert('Upload foto bill');
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Prevent same payment method
    primaryPaymentMethod.addEventListener('change', function() {
        const selectedMethod = this.value;
        Array.from(secondaryPaymentMethod.options).forEach(option => {
            option.disabled = option.value === selectedMethod;
        });
    });

    // ...existing event listeners...
});
</script>
