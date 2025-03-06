<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="brand-selection mb-4">
        <h4 class="h5 align-middle m-0 font-weight-bold text-primary" style="padding: 20px 0 10px 20px;">Select Brand</h4>
        <div class="row gap-3" style="padding: 20px 0 0 20px;">
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/terasjapan.jpeg" class="img-fluid" alt="Brand 1">
            </div>
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/tottori.jpeg" class="img-fluid" alt="Brand 2">
            </div>
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/amigos.jpeg" class="img-fluid" alt="Brand 3">
            </div>
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/toyotomi.jpeg" class="img-fluid" alt="Brand 4">
            </div>
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/toyofuku.jpeg" class="img-fluid" alt="Brand 5">
            </div>
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/wataame.jpeg" class="img-fluid" alt="Brand 6">
            </div>
            <div class="col-md-2 col-4 mb-3">
                <img src="../assets/image/logo/pokapoka.jpeg" class="img-fluid" alt="Brand 7">
            </div>
            <div class="col-md-2 col-4 mb-3 d-flex align-items-center justify-content-center">
                <div class="social-icons">
                    <i class='bx bx-plus' style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
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