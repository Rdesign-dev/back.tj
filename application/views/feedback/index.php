<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Feedback Member
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($feedback as $fb) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $fb->category ?></td>
                        <td>
                            <?php 
                            for($i = 1; $i <= 5; $i++) {
                                echo ($i <= $fb->rating) ? 
                                    '<i class="fas fa-star text-warning"></i>' : 
                                    '<i class="far fa-star text-warning"></i>';
                            }
                            ?>
                        </td>
                        <td><?= $fb->feedback_text ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($fb->created_at)) ?></td>
                        <td>
                            <a href="<?= base_url('feedback/delete/' . $fb->id) ?>" 
                               class="btn btn-sm btn-circle btn-danger" 
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="Hapus Feedback">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


