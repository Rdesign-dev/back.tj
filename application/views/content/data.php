<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Popup Content
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('content/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Add Content
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
                    <th>Name</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($content) :
                    foreach ($content as $row) :
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td>
                            <img src="<?= base_url('../ImageTerasJapan/contentpopup/') . $row['Image']; ?>" 
                                 alt="<?= $row['name']; ?>" 
                                 width="100px" 
                                 height="100px">
                        </td>
                        <td><?= $row['link']; ?></td>
                        <td><?= $row['status']; ?></td>
                        <td>
                            <a href="<?= base_url('content/edit/') . $row['id'] ?>" 
                               class="btn btn-circle btn-sm btn-warning" 
                               title="Edit">
                                <i class="fa fa-fw fa-edit"></i>
                            </a>
                            <a href="<?= base_url('content/toggle/') . $row['id'] ?>" 
                               class="btn btn-circle btn-sm <?= $row['status'] == 'Active' ? 'btn-success' : 'btn-secondary' ?>" 
                               title="<?= $row['status'] == 'Active' ? 'Set Inactive' : 'Set Active' ?>">
                                <i class="fa fa-fw fa-power-off"></i>
                            </a>
                            <a onclick="return confirm('Are you sure want to delete?')" 
                               href="<?= base_url('content/delete/') . $row['id'] ?>" 
                               class="btn btn-circle btn-sm btn-danger" 
                               title="Delete">
                                <i class="fa fa-fw fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php 
                    endforeach;
                else : ?>
                    <tr>
                        <td colspan="6" class="text-center">No popup content found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>