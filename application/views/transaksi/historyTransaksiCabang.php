<?= $this->session->flashdata('pesan'); ?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Data History Transaksi
                        </h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap" id="dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
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
                        if ($trans) :
                            foreach ($trans as $t) :
                        ?>
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
                                            echo '<span class="badge badge-info mr-1">' . $payment . '</span>';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <img width="50" src="https://terasjapan.com/ImageTerasJapan/transaction_proof/Payment/<?= $t->transaction_evidence ?>" 
                                         alt="Bukti" class="img-thumbnail">
                                </td>
                                <td><?= $t->kode_voucher ?? '-'; ?></td>
                                <td><?= $t->cashier_name; ?></td>
                            </tr>
                            <?php endforeach;
                        else : ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada transaksi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>