<!-- filepath: c:\laragon\www\back.tj\application\views\level\index.php -->
<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Level Member
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No.</th>
                    <th>Nama Level</th>
                    <th>Title</th>
                    <th>Deskripsi</th>
                    <th>Minimal Spending</th>
                    <th class="text-center text-nowrap" style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $no = 1;
            if ($levels) :
                foreach ($levels as $level) :
            ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= $level['level_name']; ?></td>
                    <td><?= $level['title']; ?></td>
                    <td><?= $level['description']; ?></td>
                    <td>Rp <?= number_format($level['min_spending'], 2); ?></td>
                    <td class="text-nowrap text-center">
                        <!-- Edit Button -->
                        <a href="<?= base_url('level/edit/'.$level['id']) ?>"
                            class="btn btn-warning btn-sm rounded-circle"
                            data-bs-toggle="tooltip"
                            title="Edit">
                            <i class="fa fa-fw fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach;
            else : ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data Level.</td>
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