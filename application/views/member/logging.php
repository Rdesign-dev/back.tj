<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Tracking Login Member
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Nomor Handphone</th>
                    <th>Nama Member</th>
                    <th>Tanggal Login</th>
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
                            <td><?= $log->tanggallogin; ?></td>
                            
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Data Login Member tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>