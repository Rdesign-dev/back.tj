<?= $this->session->flashdata('pesan'); ?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Riwayat Transaksi <?= userdata('namacabang') ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Member</th>
                    <th>Total Pembelian</th>
                    <th>Nama Kasir</th>
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
                            <td><?= $tran->namamember ?></td>
                            <td><?= $tran->total ?></td>
                            <td><span class="text-capitalize"><?= $tran->nama ?></span></td>
                            <td><?= $tran->kodevoucher ?></td>
                            <td><img src="https://terasjapan.com/fotobill/<?= $tran->fotobill ?>" alt="" width="150px" height="100px"></td>
                        </tr>
                        <?php
                    }?>
            </tbody>
        </table>
    </div>
        </div>
    </div>
</div>