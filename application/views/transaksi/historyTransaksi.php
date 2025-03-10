<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Riwayat Transaksi
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('transaksi') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Transaksi
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Cabang</th>
                    <th>Nama Member</th>
                    <th>Nama Kasir</th>
                    <th>Total</th>
                    <th>Voucher</th>
                    <th>Foto Bill</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($trans as $tr => $tran) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $tran->transaction_codes ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($tran->created_at)) ?></td>
                        <td><?= $tran->branch_name ?></td>
                        <td><?= $tran->member_name ?></td>
                        <td><?= $tran->cashier_name ?></td>
                        <td>Rp <?= number_format($tran->amount, 0, ',', '.') ?></td>
                        <td>-</td>
                        <td>
                            <?php if($tran->transaction_evidence): ?>
                                <img src="<?= base_url('../ImageTerasJapan/transaction_evidence/' . $tran->transaction_evidence) ?>" 
                                     alt="Bill" width="150px" height="100px">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>