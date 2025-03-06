<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Brand
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('brand/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Brand
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
                    <th>Nama Brand</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($brands) :
                    foreach ($brands as $brand) :
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $brand['nama_brand']; ?></td>
                        <td><?= $brand['deskripsi']; ?></td>
                        <td>
                            <a href="<?= base_url('brand/edit/') . $brand['id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                            <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('brand/delete/') . $brand['id'] ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="4" class="text-center">Silahkan tambahkan brand baru</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>