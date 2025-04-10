<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Daily Login Rewards Settings
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Day Number</th>
                    <th>Points</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($rewards) :
                    foreach ($rewards as $reward) :
                ?>
                    <tr>
                        <td><?= $reward->id; ?></td>
                        <td>Day <?= $reward->day_number; ?></td>
                        <td>
                            <form action="<?= base_url('loginstreak/update'); ?>" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= $reward->id; ?>">
                                <input type="number" name="points" value="<?= $reward->points; ?>" class="form-control form-control-sm d-inline" style="width: 80px;">
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <td><?= $reward->points; ?> Points</td>
                    </tr>
                <?php 
                    endforeach;
                else : 
                ?>
                    <tr>
                        <td colspan="4" class="text-center">No rewards data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>