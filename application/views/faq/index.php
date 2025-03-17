<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data FAQ
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('faq/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah FAQ
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                    <th class="text-center" style="width: 15%;">Status</th>
                    <th class="text-center" style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($faqs) :
                    foreach ($faqs as $faq) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= $faq['question']; ?></td>
                            <td><?= $faq['answer']; ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('faq/toggle_status/') . $faq['id'] ?>" 
                                   class="btn btn-circle btn-sm <?= $faq['status'] == 'Active' ? 'btn-success' : 'btn-secondary' ?>" 
                                   title="<?= $faq['status'] == 'Active' ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                    <i class="fa fa-fw <?= $faq['status'] == 'Active' ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('faq/edit/') . $faq['id'] ?>" 
                                   class="btn btn-circle btn-sm btn-warning"
                                   title="Edit">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                                <a onclick="return confirm('Yakin ingin menghapus data?')" 
                                   href="<?= base_url('faq/delete/') . $faq['id'] ?>" 
                                   class="btn btn-circle btn-sm btn-danger"
                                   title="Hapus">
                                    <i class="fa fa-fw fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data FAQ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>