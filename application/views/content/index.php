<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Konten PopUp
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('content/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Konten
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
                    <th>Konten</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($contents) :
                    foreach ($contents as $content) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><img src="https://terasjapan.com/fotokonten/<?= $content['gambar'] ?>" alt="" width="100px" height="100px"></td>
                            <td><?= $content['konten']; ?></td>
                            <td>
                                <?php
                                if ($content['isActive'] == 0) {
                                    echo "Tidak Aktif";
                                } elseif ($content['isActive'] == 1) {
                                    echo "Aktif";
                                } else {
                                    echo "Undefined";
                                }
                                ?>
                            </td>
                            <td><a href="<?= base_url('content/toggle/') . $content['id'] ?>" class="btn btn-circle btn-sm <?= $content['isActive'] ? 'btn-success' : 'btn-secondary' ?>" title="<?= $content['isActive'] ? 'Nonaktifkan User' : 'Aktifkan User' ?>"><i class="fa fa-fw fa-power-off"></i></a>
                                <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('content/delete/') . $content['id'] ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a></td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Silahkan tambahkan konten popup baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>