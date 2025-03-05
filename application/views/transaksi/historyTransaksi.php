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
                    foreach ($trans as $tr => $tran) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $tran->kodetransaksi ?></td>
                            <td><?= $tran->tanggaltransaksi ?></td>
                            <td><?= $tran->namacabang ?></td>
                            <td><?= $tran->namamember ?></td>
                            <td><?= $tran->nama?></td>
                            <td>Rp. <?= number_format($tran->total, 0, ',', '.') ?></td>
                            <td><?= $tran->kodevoucher ?></td>
                            <td><img src="https://terasjapan.com/fotobill/<?= $tran->fotobill ?>" alt="" width="150px" height="100px"></td>
                        </tr>
                        <?php
                    }?>
            </tbody>
        </table>
    </div>
</div>