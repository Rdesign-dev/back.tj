<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Brand Detail
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('iklan/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Brand
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No.</th>
                    <th style="width: 15%;">Nama Brand</th>
                    <th style="width: 20%; word-wrap: break-word;">Deskripsi</th>
                    <th class="text-center" style="width: 20%;">Logo</th>
                    <th class="text-center" style="width: 20%;">Banner</th>
                    <th style="width: 20%; word-wrap: break-word;">Link Instagram</th>
                    <th style="width: 20%; word-wrap: break-word;">Link Tiktok</th>
                    <th style="width: 20%; word-wrap: break-word;">Link WA</th>
                    <th style="width: 20%; word-wrap: break-word;">Link Web</th>
                    <th class="text-center text-nowrap" style="width: 20%;">Aksi</th>

                </tr>
            </thead>
            <tbody>
                <?php
            $no = 1;
            if ($iklans) :
                foreach ($iklans as $iklan) :
					// var_dump($iklan);
					// die();
            ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= $iklan['title']; ?></td>
                    <td style="max-width: 400px; word-wrap: break-word; white-space: normal;">
                        <?= $iklan['description']; ?>
                    </td>
                    <td class="text-center">
                        <img src="<?= base_url('../ImageTerasJapan/promo/') . $iklan['image_name'] ?>" alt="Promo Image"
                            class="img-fluid" style="max-width: 150px; height: auto;">
                    </td>
                    <td class="text-nowrap text-center d-flex justify-content-center gap-2 flex-wrap">
                        <!-- Edit Button -->
                        <a href="<?= base_url('iklan/edit_iklan/') . $iklan['id'] ?>"
                            class="btn btn-warning btn-sm rounded-circle" data-bs-toggle="tooltip" title="Edit">
                            <i class="fa fa-fw fa-edit"></i>
                        </a>
                        <!-- Delete Button -->
                        <a onclick="return confirm('Yakin ingin menghapus data?')"
                            href="<?= base_url('iklan/delete/') . $iklan['id'] ?>"
                            class="btn btn-danger btn-sm rounded-circle" data-bs-toggle="tooltip" title="Hapus">
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                        <!-- Toggle Status Button -->
                        <a href="<?= base_url('iklan/toggle_status/') . $iklan['id'] ?>"
                            class="btn btn-sm rounded-circle <?= isset($iklan['status']) && $iklan['status'] == 'Active' ? 'btn-success' : 'btn-danger' ?>"
                            data-bs-toggle="tooltip"
                            title="Status: <?= isset($iklan['status']) ? $iklan['status'] : 'inactive' ?>">
                            <i
                                class="fa fa-fw <?= isset($iklan['status']) && $iklan['status'] == 'Active' ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach;
            else : ?>
                <tr>
                    <td colspan="5" class="text-center">Silahkan tambahkan Brand</td>
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