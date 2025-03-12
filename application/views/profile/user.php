<div class="card p-2 shadow-sm border-bottom-primary">
    <div class="card-header bg-white">
        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
            <?= $user['Name']; ?>
        </h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 mb-4 mb-md-0">
                <img src="<?= base_url() ?>../ImageTerasJapan/ProfPic/<?= $user['photo']; ?>" alt="" class="img-thumbnail rounded mb-2">
                <a href="<?= base_url('profile/setting'); ?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-edit"></i> Edit Profile</a>
                <a href="<?= base_url('profile/ubahpassword'); ?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-lock"></i> Ubah Password</a>
            </div>
            <div class="col-md-10">
                <table class="table">
                    <tr>
                        <th width="200">Username</th>
                        <td><?= $user['username']; ?></td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon</th>
                        <td><?= $user['phone_number']; ?></td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td class="text-capitalize"><?= str_replace('_', ' ', $user['account_type']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge badge-<?= $user['status'] == 'Active' ? 'success' : 'danger' ?>"><?= $user['status']; ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>