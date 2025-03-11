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
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggaltransaksi">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('tanggaltransaksi'); ?>" type="datetime-local" id="tanggaltransaksi" name="tanggaltransaksi" class="form-control" placeholder="tanggaltransaksi">
                        <span class="text-danger small"><?= form_error('tanggaltransaksi'); ?></span>
                    </div>
                </div>
                
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
                                <input type="text" 
                                       class="form-control" 
                                       name="nomor" 
                                       id="nomor" 
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
                                <input type="text" 
                                       class="form-control" 
                                       name="nama" 
                                       id="nama" 
                                       value="<?= isset($data['name']) ? $data['name'] : ''; ?>" 
                                       readonly>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <span class="text-danger small"><?= form_error('nama'); ?></span>
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
                                        <option value="<?= $voucher['kode_voucher'] ?>"><?= $voucher['kode_voucher'] ?> (Points: <?= $voucher['points_used'] ?>)</option>
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
                            <input value="<?= set_value('total'); ?>" type="text" id="total" name="total" class="form-control" placeholder="Masukkan Total Contoh: 10000" pattern="[0-9]+" title="Hanya boleh diisi oleh angka, dan minimal 4 digit angka">
                            <span class="text-danger small"><?= form_error('total'); ?></span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="payment_method">Metode Pembayaran</label>
                        <div class="col-md-6">
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transferBank">Transfer Bank</option>
                                <option value="Balance">Saldo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="fotobill">Foto Bill</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('fotobill'); ?>" type="file" id="fotobill" name="fotobill" class="form-control">
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
document.addEventListener("DOMContentLoaded", function () {
    // Get all required elements
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const divKodevoucher = document.getElementById('divKodevoucher');
    const nonVoucherFields = document.getElementById('nonVoucherFields');
    const memberForm = document.getElementById('memberForm');
    const totalInput = document.getElementById('total');
    const paymentMethodSelect = document.getElementById('payment_method');
    const fotobillInput = document.getElementById('fotobill');

    // Function to toggle fields
    function toggleFields(isVoucherMode) {
        divKodevoucher.style.display = isVoucherMode ? 'block' : 'none';
        nonVoucherFields.style.display = isVoucherMode ? 'none' : 'block';
        
        // Handle form validation requirements
        if (isVoucherMode) {
            totalInput.required = false;
            totalInput.value = '';
            paymentMethodSelect.required = false;
            paymentMethodSelect.value = 'cash'; // default value
        } else {
            totalInput.required = true;
            paymentMethodSelect.required = true;
        }
    }

    // Initial state
    toggleFields(tukarVoucherCheckbox.checked);

    // Handle checkbox changes
    tukarVoucherCheckbox.addEventListener('change', function() {
        toggleFields(this.checked);
    });

    // Form validation
    memberForm.addEventListener("submit", function (event) {
        let isValid = true;

        // Validate based on mode
        if (!tukarVoucherCheckbox.checked) {
            // Normal transaction mode
            if (!totalInput.value.trim()) {
                totalInput.classList.add("is-invalid");
                isValid = false;
            }
            if (!paymentMethodSelect.value) {
                paymentMethodSelect.classList.add("is-invalid");
                isValid = false;
            }
        } else {
            // Voucher mode
            if (!document.getElementById('kodevouchertukar').value) {
                document.getElementById('kodevouchertukar').classList.add("is-invalid");
                isValid = false;
            }
        }

        // Always validate file upload
        if (!fotobillInput.value.trim()) {
            fotobillInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    // Remove invalid class on input
    totalInput.addEventListener("input", function() {
        this.classList.remove("is-invalid");
    });

    paymentMethodSelect.addEventListener("change", function() {
        this.classList.remove("is-invalid");
    });

    document.getElementById('kodevouchertukar').addEventListener("change", function() {
        this.classList.remove("is-invalid");
    });

    fotobillInput.addEventListener("input", function() {
        this.classList.remove("is-invalid");
    });
});
</script>


