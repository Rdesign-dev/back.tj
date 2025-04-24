<?= $this->session->flashdata('pesan'); ?>
<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Data Benefit Level</h4>
    <a href="<?= base_url('benefit/add'); ?>" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> Tambah Benefit
    </a>
    <?php if ($levels): ?>
        <?php foreach ($levels as $level): ?>
            <div class="card shadow-sm mb-4 border-bottom-success">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        Benefit Level <?= $level['level_name']; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="width:30%">Benefit Title</th>
                                    <th>Benefit Description</th>
                                    <th style="width:120px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($benefits[$level['id']])): ?>
                                    <?php foreach ($benefits[$level['id']] as $benefit): ?>
                                        <tr>
                                            <td><?= $benefit['benefit_title']; ?></td>
                                            <td><?= $benefit['benefit_description']; ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url('benefit/edit/'.$benefit['id']); ?>" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('benefit/delete/'.$benefit['id']); ?>" class="btn btn-danger btn-sm" title="Delete"
                                                   onclick="return confirm('Yakin ingin menghapus benefit ini?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada benefit untuk level ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Tidak ada data level.</div>
    <?php endif; ?>
</div>