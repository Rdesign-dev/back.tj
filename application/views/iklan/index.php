<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Promo Mingguan
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('iklan/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Promo
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
                    <th>Nama Promo</th>
                    <th>Deskripsi</th>
                    <th>Foto Promo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($iklans) :
                    foreach ($iklans as $iklan) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $iklan['title']; ?></td>
                            <td><?= $iklan['description']; ?></td>
                            <td><img src="<?= base_url('../ImageTerasJapan/promo/') . $iklan['image_name'] ?>" alt="" width="150px" height="100px"></td>
                            <td>
                                <a href="<?= base_url('iklan/edit_iklan/') . $iklan['id'] ?>" class="btn btn-circle btn-sm btn-warning">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                                <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('iklan/delete/') . $iklan['id'] ?>" class="btn btn-circle btn-sm btn-danger">
                                    <i class="fa fa-fw fa-trash"></i>
                                </a>
                                <a href="<?= base_url('iklan/toggle_status/') . $iklan['id'] ?>" 
                                   class="btn btn-circle btn-sm <?= isset($iklan['status']) && $iklan['status'] == 'Active' ? 'btn-success' : 'btn-secondary' ?>">
                                    <i class="fa fa-fw <?= isset($iklan['status']) && $iklan['status'] == 'Active' ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Silahkan tambahkan Promo</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>