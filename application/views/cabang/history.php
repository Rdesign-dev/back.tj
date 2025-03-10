<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Riwayat Transaksi - <?= $branch['branch_name'] ?>
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('cabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap" id="dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($trans) : 
                            foreach ($trans as $t) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $t['transaction_codes'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                <td>Rp <?= number_format($t['amount'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach;
                        else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada transaksi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>