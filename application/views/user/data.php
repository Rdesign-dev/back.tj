<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data User
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('user/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-user-plus"></i>
                    </span>
                    <span class="text">
                        Tambah User
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
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nomor Telepon</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($users) :
                    foreach ($users as $user) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <img width="30" src="<?= base_url() ?>../ImageTerasJapan/ProfPic/<?= $user['photo'] ?>" 
                                     alt="<?= $user['Name']; ?>" class="img-thumbnail rounded-circle">
                            </td>
                            <td><?= $user['Name']; ?></td>
                            <td><?= $user['username']; ?></td>
                            <td><?= ucwords(str_replace('_', ' ', $user['account_type'])); ?></td>
                            <td><?= $user['phone_number']; ?></td>
                            <td><?= $user['branch_name'] ?? 'Teras Japan Pusat'; ?></td>
                            <td>
                                <a href="<?= base_url('user/toggle/') . $user['id'] ?>" 
                                   class="btn btn-circle btn-sm <?= $user['status'] == 'Active' ? 'btn-success' : 'btn-secondary' ?>" 
                                   title="<?= $user['status'] == 'Active' ? 'Nonaktifkan User' : 'Aktifkan User' ?>">
                                    <i class="fa fa-fw fa-power-off"></i>
                                </a>
                            </td>
                            <td>
                                <a href="<?= base_url('user/edit/') . $user['id'] ?>" 
                                   class="btn btn-circle btn-sm btn-warning">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                                <a onclick="return confirm('Yakin ingin menghapus data?')" 
                                   href="<?= base_url('user/delete/') . $user['id'] ?>" 
                                   class="btn btn-circle btn-sm btn-danger">
                                    <i class="fa fa-fw fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="9" class="text-center">Silahkan tambahkan user baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>