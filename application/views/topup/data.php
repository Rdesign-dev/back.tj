<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data TopUp Saldo Member
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('transaksi/saldo') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Topup Saldo
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Nominal</th>
                    <th>Metode Pembayaran</th>
                    <th>Foto Bukti</th>
                    <th>Nama member</th>
                    <th>Nama User</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($tops) :
                    foreach ($tops as $top) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>Rp <?= number_format($top->nominal, 0, ',', '.'); ?></td>
                            <td><?= $top->metode; ?></td>
                            <td>
                                <?php if($top->bukti && $top->bukti != 'struk.png'): ?>
                                    <img src="<?= base_url('../ImageTerasJapan/transaction_evidence/' . $top->bukti) ?>" 
                                         alt="Bukti Transfer" width="100px" height="100px">
                                <?php else: ?>
                                    <span class="text-muted">Cash Payment</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $top->namamember; ?></td>
                            <td><?= $top->nama; ?></td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Silahkan tambahkan data topup baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>