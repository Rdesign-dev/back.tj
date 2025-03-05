<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Undian
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Nomor</th>
                    <th>Nama Member</th>
                    <th>Tanggal Penukaran</th>
                    <th>Poin</th>
                    <th>Poin Member</th>
                    <th>Sisa Poin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($undians) :
                    foreach ($undians as $undian) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $undian['nomor']; ?></td>
                            <td><?= $undian['namamember']; ?></td>
                            <td><?= $undian['tanggalpenukaran']; ?></td>
                            <td><?= $undian['poin']; ?></td>
                            <td><?= $undian['poinmember']; ?></td>
                            <td><?= $undian['poinmember'] - $undian['poin']; ?></td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data undian</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>