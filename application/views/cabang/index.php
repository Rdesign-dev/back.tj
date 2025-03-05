<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Cabang
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('cabang/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Cabang
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
                    <th>Kode Cabang</th>
                    <th>Nama Cabang</th>
                    <th>Alamat</th>
                    <th>Jumlah Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($cabangs) :
                    foreach ($cabangs as $cabang) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $cabang['branch_code']; ?></td>
                            <td><?= $cabang['branch_name']; ?></td>
                            <td><?= $cabang['address']; ?></td>
                            <td><?= $cabang['transaction_count'];?></td>
                            <td><a href="<?= base_url("cabang/getTransaksiCabang/{$cabang['id']}")?>" class="btn btn-primary"><i class="fas fa-info-circle"></i> Detail</a>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Silahkan tambahkan cabang baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>