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
                    <label class="col-md-4 text-md-right">Tipe Transaksi</label>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="tukarVoucher" name="tukarVoucher">
                            <label class="custom-control-label" for="tukarVoucher">Tukar Voucher</label>
                        </div>
                    </div>
                </div>

                <div id="normalTransaction">
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Total</label>
                        <div class="col-md-6">
                            <input type="number" name="total" class="form-control" min="1000">
                            <small class="text-muted">Minimal transaksi Rp 1.000</small>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Metode Pembayaran</label>
                        <div class="col-md-6">
                            <select name="payment_method" class="form-control">
                                <option value="CSH">Cash</option>
                                <option value="TFB">Transfer Bank</option>
                                <option value="BM">Balance Member</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="voucherTransaction" style="display:none;">
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right">Pilih Voucher</label>
                        <div class="col-md-6">
                            <select name="kodevouchertukar" class="form-control">
                                <option value="">Pilih Voucher</option>
                                <?php foreach ($unused_vouchers as $voucher): ?>
                                <option value="<?= $voucher->redeem_id ?>"><?= $voucher->kode_voucher ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right">Tanggal Transaksi</label>
                    <div class="col-md-6">
                        <input type="datetime-local" name="tanggaltransaksi" class="form-control" required>
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
$(document).ready(function() {
    // Set default datetime to now
    document.getElementById('tanggaltransaksi').value = new Date().toISOString().slice(0, 16);

    // Toggle transaction sections
    $('#tukarVoucher').change(function() {
        if ($(this).is(':checked')) {
            $('#voucherTransaction').show();
            $('#normalTransaction').hide();
        } else {
            $('#voucherTransaction').hide();
            $('#normalTransaction').show();
        }
    });

    // Form validation
    $('#transactionForm').submit(function(e) {
        if (!$('#tukarVoucher').is(':checked')) {
            let amount = $('input[name="total"]').val();
            if (amount < 1000) {
                e.preventDefault();
                alert('Minimal transaksi Rp 1.000');
                return;
            }

            let payment_method = $('select[name="payment_method"]').val();
            if (payment_method === 'BM') {
                let balance = <?= $member->balance ?>;
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