<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    History Top Up Saldo
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Cabang</th>
                    <th>Nama Member</th>
                    <th>Kasir</th>
                    <th>Jumlah Top Up</th>
                    <th>Metode Pembayaran</th>
                    <th>Bukti Transfer</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($trans)) : ?>
                <?php $no = 1; foreach ($trans as $tran) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $tran->transaction_codes ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($tran->created_at)) ?></td>
                    <td><?= $tran->branch_name ?></td>
                    <td><?= $tran->member_name ?></td>
                    <td><?= $tran->cashier_name ?></td>
                    <td>Rp <?= number_format($tran->amount, 0, ',', '.') ?></td>
                    <td><?= $tran->payment_method ?></td>
                    <td>
                        <?php if($tran->transaction_evidence != null): ?>
                        <img src='<?= base_url('../ImageTerasJapan/transaction_proof/Topup/') . $tran->transaction_evidence ?>'
                            alt="Transfer Evidence" width="150" height="100">
                        <?php else: ?>
                        <img src='<?= base_url('../ImageTerasJapan/transaction_proof/struk.png') ?>' alt="Transfer Evidence"
                            width="150" height="100">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data topup</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>