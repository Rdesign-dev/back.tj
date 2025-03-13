<?= $this->session->flashdata('pesan'); ?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Riwayat Transaksi <?= userdata('namacabang') ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Tipe Transaksi</th>
                            <th>Nama Member</th>
                            <th>Total</th>
                            <th>Metode Pembayaran</th>
                            <th>Kode Voucher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($trans as $t): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $t->transaction_codes; ?></td>
                            <td><?= $t->transaction_type; ?></td>
                            <td><?= $t->member_name; ?></td>
                            <td>Rp <?= number_format($t->amount, 0, ',', '.'); ?></td>
                            <td><?= $t->payment_method; ?></td>
                            <td><?= $t->kode_voucher ?? '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>