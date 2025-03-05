<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form <?= $title; ?>
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('member') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?php if ($member) : ?>
                <img src="https://terasjapan.com/fotouser/<?= $member->foto ?>" alt="User Cw" width="150" height="150">
                <?php endif; ?>
                <br>
                <br>
                <table>
                <?php if ($member) : ?>
                    <tr>
                        <td>Nama Member</td>
                        <td>:</td>
                        <td><strong><?= $member->namamember ?></strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon</td>
                        <td>:</td>
                        <td><strong><?= $member->nomor ?></strong></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><strong><?= $member->alamat ?></strong></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td><strong><?= $member->email?></strong></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td><strong><?= $member->jeniskelamin ?></strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>:</td>
                        <td><strong><?= $member->tanggallahir ?></strong></td>
                    </tr>
                    <tr>
                        <td>Tempat Lahir</td>
                        <td>:</td>
                        <td><strong><?= $member->tempatlahir ?></strong></td>
                    </tr>
                    <tr>
                        <td>Poin</td>
                        <td>:</td>
                        <td><strong><?= $member->poin ?></strong></td>
                    </tr>
                    <tr>
                        <td>Saldo</td>
                        <td>:</td>
                        <td><strong>Rp. <?= number_format($member->saldo, 0, ',', '.') ?></strong></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Cabang</th>
                    <th>Nama Kasir</th>
                    <th>Total</th>
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
                            <td><?= $tran->nama?></td>
                            <td>Rp. <?= number_format($tran->total, 0, ',', '.') ?></td>
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