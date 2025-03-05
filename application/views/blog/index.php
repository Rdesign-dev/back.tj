<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    News & Event
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('blog/tambah_save') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah News & Event
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
                    <th>Caption</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
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
                        <td>
                            <img src="<?= base_url('../ImageTerasJapan/news_event/') . $blog['image'] ?>" 
                                 alt="News Image" 
                                 class="img-thumbnail"
                                 style="max-width: 150px; height: auto;">
                        </td>
                        <td><?= $blog['title']; ?></td>
                        <td><?= $blog['captions']; ?></td>
                        <td><?= $blog['description']; ?></td>
                        <td>
                            <a href="<?= base_url('blog/toggle_status/') . $blog['id'] ?>" 
                               class="btn btn-circle btn-sm <?= $blog['status'] == 'Active' ? 'btn-success' : 'btn-secondary' ?>" 
                               title="<?= $blog['status'] == 'Active' ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                <i class="fa fa-fw <?= $blog['status'] == 'Active' ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                            </a>
                        </td>
                        <td>
                            <a href="<?= base_url('blog/edit_blog/') . $blog['id'] ?>" 
                               class="btn btn-circle btn-sm btn-warning"
                               title="Edit">
                                <i class="fa fa-fw fa-edit"></i>
                            </a>
                            <a onclick="return confirm('Yakin ingin menghapus data?')" 
                               href="<?= base_url('blog/delete/') . $blog['id'] ?>" 
                               class="btn btn-circle btn-sm btn-danger"
                               title="Hapus">
                                <i class="fa fa-fw fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data News & Event</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>