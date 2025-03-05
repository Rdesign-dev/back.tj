<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Member
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('member/tambahCabang') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-user-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Member
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
                    <th>Nama Member</th>
                    <th>Nomor Handphone</th>
                    <th>Poin</th>
                    <th>Saldo</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($members) :
                    foreach ($members as $member) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $member['namamember']; ?></td>
                            <td><?= $member['nomor']; ?></td>
                            <td><?= $member['poin']; ?></td>
                            <td>Rp. <?= number_format($member['saldo'], 0, ',', '.') ?></td>
                            <td><?= $member['tanggaldaftar']; ?></td>
                            <td><a href="<?= base_url("member/detailCabang/{$member['nomor']}")?>" class="btn btn-primary"><i class="fas fa-info-circle"></i> Detail</a>
                            <a href="<?= base_url('member/edit_memberCabang/') . $member['nomor'] ?>" class="btn btn-success"><i class="fas fa-user-cog"></i> Edit</a></a></td>
                            
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Silahkan tambahkan user baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>