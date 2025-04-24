<!-- filepath: c:\laragon\www\back.tj\application\views\mission\index.php -->
<?= $this->session->flashdata('pesan'); ?>
<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Data Missions</h4>
    <a href="<?= base_url('mission/add'); ?>" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> Tambah Mission
    </a>
    <div class="card shadow-sm mb-4 border-bottom-success">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Point Reward</th>
                            <th style="width:120px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($missions)): ?>
                            <?php foreach ($missions as $mission): ?>
                                <tr>
                                    <td><?= $mission['title']; ?></td>
                                    <td><?= $mission['description']; ?></td>
                                    <td><?= $mission['point_reward']; ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('mission/edit/'.$mission['id']); ?>" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('mission/delete/'.$mission['id']); ?>" class="btn btn-danger btn-sm" title="Delete"
                                           onclick="return confirm('Yakin ingin menghapus mission ini?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada mission.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>