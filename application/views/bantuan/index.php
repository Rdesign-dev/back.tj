<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Pusat Bantuan
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('bantuan/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Pusat Bantuan
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
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Tags</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($bantuans) :
                    foreach ($bantuans as $bantuan) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bantuan['judul']; ?></td>
                            <td><?= $bantuan['isi']; ?></td>
                            <td><?= $bantuan['tags'];?></td>
                            <td><a href="<?= base_url('bantuan/edit_bantuan/') . $bantuan['id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Silahkan tambahkan data pusat bantuan baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>