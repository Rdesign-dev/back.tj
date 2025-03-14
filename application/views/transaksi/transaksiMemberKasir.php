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
                        <a href="<?= base_url('transaksikasir/tambahTransaksiKasir') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('transaksikasir/convert_and_updateKasir', [
                    'id' => 'memberForm',
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggaltransaksi">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('tanggaltransaksi'); ?>" type="datetime-local" id="tanggaltransaksi" name="tanggaltransaksi" class="form-control">
                        <?= form_error('tanggaltransaksi', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Member</label>
                    <div class="col-md-6">
                        <input type="text" name="nomor" id="nomor" class="form-control" value="<?= $member->phone_number ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <input type="text" name="namamember" class="form-control" id="namamember" value="<?= $member->name ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Poin</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" value="<?= $member->poin ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Saldo</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" value="Rp <?= number_format($member->balance, 0, ',', '.') ?>" readonly>
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

                <div class="row form-group payment-field">
                    <label class="col-md-4 text-md-right" for="payment_method">Metode Pembayaran</label>
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

                <div class="row form-group payment-field">
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
document.addEventListener('DOMContentLoaded', function() {
    const memberForm = document.getElementById('memberForm');
    const tukarVoucherCheckbox = document.getElementById('tukarVoucher');
    const kodevouchertukar = document.getElementById('kodevouchertukar');
    const paymentFields = document.querySelectorAll('.payment-field');
    const payment_method = document.getElementById('payment_method');
    const total = document.getElementById('total');
    const tanggaltransaksiInput = document.getElementById('tanggaltransaksi');
    const fotobillInput = document.getElementById('fotobill');

    tukarVoucherCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;

        paymentFields.forEach(field => {
            field.style.display = isChecked ? 'none' : 'block';
        });
        kodevouchertukar.disabled = !isChecked;

        payment_method.required = !isChecked;
        total.required = !isChecked;
        kodevouchertukar.required = isChecked;

        if (!isChecked) {
            kodevouchertukar.value = '';
        } else {
            payment_method.value = '';
            if (total) total.value = '';
        }
    });

    memberForm.addEventListener("submit", function (event) {
        console.log('Form submitted');
        console.log('Form data:', new FormData(memberForm));

        if (tukarVoucherCheckbox.checked) {
            if (!kodevouchertukar.value.trim()) {
                event.preventDefault();
                kodevouchertukar.classList.add("is-invalid");
            } else {
                kodevouchertukar.classList.remove("is-invalid");
            }
        } else {
            if (!tanggaltransaksiInput.value.trim()) {
                event.preventDefault();
                tanggaltransaksiInput.classList.add("is-invalid");
            } else {
                tanggaltransaksiInput.classList.remove("is-invalid");
            }

            if (!total.value.trim()) {
                event.preventDefault();
                total.classList.add("is-invalid");
            } else {
                total.classList.remove("is-invalid");
            }

            if (!payment_method.value.trim()) {
                event.preventDefault();
                payment_method.classList.add("is-invalid");
            } else {
                payment_method.classList.remove("is-invalid");
            }

            if (!fotobillInput.value.trim()) {
                event.preventDefault();
                fotobillInput.classList.add("is-invalid");
            } else {
                fotobillInput.classList.remove("is-invalid");
            }
        }
    });
});
</script>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>