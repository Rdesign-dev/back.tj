<?= $this->session->flashdata('pesan'); ?>
    <div class="brand-selection bg-white mb-4 shadow-sm">
        <h4 class="h5 align-middle m-0 font-weight-bold text-primary" style="padding: 20px 0 10px 20px;">Select Brand</h4>
        <div class="row gap-1" style="padding: 20px 0 20px 20px;">
            <?php if ($brands) :
                foreach ($brands as $brand) : ?>
                    <div class="col-md-1 col-2 mb-1" style="cursor: pointer;">
                        <img src="<?= base_url('../ImageTerasJapan/logo/' . $brand['image']) ?>" class="img-fluid" alt="<?= $brand['name'] ?>" style="width: 50px; height: 50px; object-fit: contain;">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="col-md-1 col-2 mb-3 d-flex align-items-center justify-content-center">
                <a href="<?= base_url('brand/add') ?>" class="text-decoration-none"></a>
                    <div class="btn btn-primary border" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class='bx bx-plus' style="font-size: 2rem;"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="divider"></div>
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
                        Tambah Data Brand
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