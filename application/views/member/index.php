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
                <a href="<?= base_url('member/tambah') ?>" class="btn btn-sm btn-primary btn-icon-split">
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
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Nomor HP</th>
                    <th>Email</th>
                    <th>Saldo</th>
                    <th>Poin</th>
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
                            <td>
                                <img src="<?= base_url('../ImageTerasJapan/ProfPic/' . ($member['profile_pic'] ?? 'profile.jpg')) ?>" 
                                     alt="Profile" class="rounded-circle" width="45" height="45">
                            </td>
                            <td><?= $member['name']; ?></td>
                            <td><?= $member['phone_number']; ?></td>
                            <td><?= $member['email']; ?></td>
                            <td>Rp <?= number_format($member['balance'], 0, ',', '.'); ?></td>
                            <td><?= number_format($member['poin'], 0, ',', '.'); ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($member['registration_time'])); ?></td>
                            <td>
                                <a href="<?= base_url('member/detail/') . $member['phone_number'] ?>" 
                                   class="btn btn-circle btn-sm btn-info" title="Detail">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a href="<?= base_url('member/edit/') . $member['id'] ?>" 
                                   class="btn btn-circle btn-sm btn-warning" title="Edit">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center">Data Kosong</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>