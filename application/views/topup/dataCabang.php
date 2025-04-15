<?= $this->session->flashdata('pesan'); ?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Riwayat Top Up Saldo
                        </h4>
                    </div>
                    <div class="col-auto">
                        <!-- <a href="<?= base_url('transaksicabang/saldoCabang') ?>" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="text">
                                Tambah Top Up
                            </span>
                        </a> -->
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
                            <th>Nominal</th>
                            <th>Kasir</th>
                            <th>Bukti Transaksi</th> <!-- New column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($trans): foreach ($trans as $t): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $t->transaction_codes; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($t->created_at)); ?></td>
                            <td><?= $t->member_name; ?></td>
                            <td>Rp <?= number_format($t->amount ?? 0, 0, ',', '.'); ?></td>
                            <td><?= $t->cashier_name; ?></td>
                            <td>
                                <?php if (!empty($t->transaction_evidence)): ?>
                                    <img src="https://terasjapan.com/ImageTerasJapan/transaction_proof/Topup/<?= $t->transaction_evidence ?>" 
                                         alt="Bukti Transaksi" 
                                         class="img-fluid" 
                                         style="max-width: 150px; max-height: 150px; cursor: pointer;"
                                         onclick="window.open(this.src)"> 
                                <?php else: ?>
                                    <span class="text-danger">Tidak Ada Bukti</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data top up</td>
                        </tr>
                        <?php endif; ?>
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