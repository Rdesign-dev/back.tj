<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    CheckIn Poin Login Member
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Hari Ke-1</th>
                    <th>Hari Ke-2</th>
                    <th>Hari Ke-3</th>
                    <th>Hari Ke-4</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($loggin) :
                    foreach ($loggin as $log) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $log->nomor; ?></td>
                            <td><?= $log->namamember; ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($log->tanggallogin)); ?></td>
                            
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="4" class="text-center">Data CheckIn Poin Login Member tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>