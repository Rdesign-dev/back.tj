<?= $this->session->flashdata('pesan'); ?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Riwayat Top Up Saldo <?= userdata('namacabang') ?>
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
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Bukti</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($trans as $t): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $t->transaction_codes; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($t->created_at)); ?></td>
                            <td><?= $t->namamember; ?></td>
                            <td>Rp <?= number_format($t->nominal, 0, ',', '.'); ?></td>
                            <td><?= $t->metode; ?></td>
                            <td>
                                <?php if($t->bukti): ?>
                                    <img src="<?= base_url('../ImageTerasJapan/transaction_proof/' . $t->bukti); ?>" width="50">
                                <?php endif; ?>
                            </td>
                            <td><?= $t->nama_kasir; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>