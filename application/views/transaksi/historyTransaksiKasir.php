<?= $this->session->flashdata('pesan'); ?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            <?= $title; ?>
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
                            <th>Tanggal</th>
                            <th>Nama Member</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Metode Pembayaran</th>
                            <th>Bukti</th>
                            <th>Kode Voucher</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if($trans): foreach($trans as $t): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $t->transaction_codes; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($t->created_at)); ?></td>
                            <td><?= $t->member_name; ?></td>
                            <td><?= $t->transaction_type; ?></td>
                            <td><?= $t->amount ? 'Rp ' . number_format($t->amount, 0, ',', '.') : '-'; ?></td>
                            <td><?= $t->payment_method ?? '-'; ?></td>
                            <td>
                                <?php if($t->transaction_evidence && $t->transaction_evidence != 'struk.png'): ?>
                                    <img src="<?= base_url('../ImageTerasJapan/transaction_proof/' . $t->transaction_evidence); ?>" width="50">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= $t->kode_voucher ?? '-'; ?></td>
                            <td><?= $t->cashier_name; ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>