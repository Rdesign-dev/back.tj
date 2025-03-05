<?php
                function generateKodeTransaksi() {
                // Logika untuk menghasilkan kode transaksi, bisa berdasarkan tanggal atau logika lainnya
                return 'TRX' . uniqid(); // Contoh sederhana, bisa disesuaikan
                }
                ?>
                <?php echo form_open_multipart('transaksi/convert_and_updateKasir', 'id="memberForm"'); ?>
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
                        <a href="<?= base_url('transaksi/tambahTransaksiKasir') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('transaksi/convert_and_updateKasir', 'id="memberForm"'); ?>

                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="kodetransaksi">Kode Transaksi</label>
                    <div class="col-md-6">
                        <input type="text" id="kodetransaksi" name="kodetransaksi" class="form-control" value="<?= generateKodeTransaksi() ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggaltransaksi">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('tanggaltransaksi'); ?>" type="datetime-local" id="tanggaltransaksi" name="tanggaltransaksi" class="form-control" placeholder="tanggaltransaksi">
                        <?= form_error('tanggaltransaksi', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Member</label>
                    <div class="col-md-6">
                        <?php if(isset($member)){
                            foreach($member as $mb => $data){
                                ?>
                                <input type="text" name="nomor" id="nomor" class="form-control" value="<?=$data['nomor']?>" readonly>
                                <?php
                            }
                        }
                        ?>
                        
                        <?= form_error('nomor', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <?php if(isset($member)){
                            foreach($member as $mb => $data){
                                ?>
                                <input type="text" name="namamember" class="form-control" id="namamember" value="<?=$data['namamember']?> " readonly>
                                <?php
                            }
                        }
                        ?>
                        
                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="penukaranvoucher">Penukaran Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('tukarVoucher'); ?>" type="checkbox" id="tukarVoucher" name="tukarVoucher"><span> Ya</span>
                        <?= form_error('tukarVoucher', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group" id="divKodevoucher" style="display: none;">
                    <label class="col-md-4 text-md-right" for="kodeVouchertukar">Kode Voucher</label>
                    <div class="col-md-6">
                        <!-- Tambahkan input field untuk cabang di sini -->
                        <select name="kodevouchertukar" id="kodevouchertukar" class="form-control">
                            <option value="" selected>Pilih Voucher</option>
                            <?php foreach ($unused_vouchers as $voucher): ?>
                                <option value="<?= $voucher['vouchergenerate'] ?>"><?= $voucher['vouchergenerate'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('kodevouchertukar', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total">Total</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('total'); ?>" type="text" id="total" name="total" class="form-control" placeholder="Masukkan Total Contoh: 10000" pattern="[0-9]+" title="Hanya boleh diisi oleh angka, dan minimal 4 digit angka">
                        <span class="text-danger small"><?= form_error('total'); ?></span>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="fotobill">Fotobill</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('fotobill'); ?>" type="file" id="fotobill" name="fotobill" class="form-control">
                        <?= form_error('fotobill', '<span class="text-danger small">', '</span>'); ?>
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
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        var memberForm = document.getElementById('memberForm');
        var tanggaltransaksiInput = document.getElementById('tanggaltransaksi');
        var totalInput = document.getElementById('total');
        var fotobillInput = document.getElementById('fotobill');

        memberForm.addEventListener("submit", function (event) {
            if (!tanggaltransaksiInput.value.trim()) {
                event.preventDefault();
                tanggaltransaksiInput.classList.add("is-invalid");
            } else {
                tanggaltransaksiInput.classList.remove("is-invalid");
            }

            if (!totalInput.value.trim()) {
                event.preventDefault();
                totalInput.classList.add("is-invalid");
            } else {
                totalInput.classList.remove("is-invalid");
            }

            if (!fotobillInput.value.trim()) {
                event.preventDefault();
                fotobillInput.classList.add("is-invalid");
            } else {
                fotobillInput.classList.remove("is-invalid");
            }
        });

        tanggaltransaksiInput.addEventListener("input", function () {
            tanggaltransaksiInput.classList.remove("is-invalid");
        });

        totalInput.addEventListener("input", function () {
            totalInput.classList.remove("is-invalid");
        });

        fotobillInput.addEventListener("input", function () {
            fotobillInput.classList.remove("is-invalid");
        });
    });
</script>