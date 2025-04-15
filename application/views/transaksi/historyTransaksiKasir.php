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
                            <td>Rp <?= number_format($t->amount, 0, ',', '.'); ?></td>
                            <td>
                                <?php 
                                if (!empty($t->payment_details)) {
                                    $payments = explode(" & ", $t->payment_details);
                                    foreach($payments as $payment) {
                                        echo '<span class="badge badge-info mr-1">';
                                        if (strpos($payment, 'cash') !== false) {
                                            echo str_replace('cash', 'Cash', $payment);
                                        } else if (strpos($payment, 'transferBank') !== false) {
                                            echo str_replace('transferBank', 'Transfer', $payment);
                                        } else if (strpos($payment, 'Balance') !== false) {
                                            echo str_replace('Balance', 'Saldo', $payment);
                                        }
                                        echo '</span>';
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if($t->transaction_evidence && $t->transaction_evidence != 'struk.png'): ?>
                                    <img src="https://terasjapan.com/ImageTerasJapan/transaction_proof/Payment/<?= $t->transaction_evidence ?>" 
                                         width="50" class="img-thumbnail" alt="Bukti"
                                         onclick="window.open(this.src)" style="cursor: pointer;">
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
    $('#dataTable').DataTable({
        responsive: true,
        order: [[2, 'desc']] // Order by date column descending
    });
});
</script>