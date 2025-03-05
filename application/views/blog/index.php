<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Blog Aplikasi
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('blog/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Blog
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
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($blogs) :
                    foreach ($blogs as $blog) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><img src="https://terasjapan.com/fotoblog/<?= $blog['gambar'] ?>" alt="" width="150px" height="100px"></td>
                            <td><?= $blog['judul']; ?></td>
                            <td><?= $blog['konten']; ?></td>
                            <td><a href="<?= base_url('blog/toggle/') . $blog['id'] ?>" class="btn btn-circle btn-sm <?= $blog['isActive'] ? 'btn-success' : 'btn-secondary' ?>" title="<?= $blog['isActive'] ? 'Nonaktifkan Blog' : 'Aktifkan Blog' ?>"><i class="fa fa-fw fa-power-off"></i></a><a href="<?= base_url('blog/edit_blog/') . $blog['id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                            <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('blog/delete/') . $blog['id'] ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Silahkan tambahkan Blog</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>