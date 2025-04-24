<!-- filepath: c:\laragon\www\back.tj\application\views\level\edit.php -->
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Edit Data Level Member
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('level') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                    <span class="icon">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">
                        Kembali
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?= form_open('level/edit/'.$level['id']); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="level_name">Nama Level</label>
                    <input type="text" id="level_name" class="form-control" value="<?= $level['level_name']; ?>" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="min_spending">Minimal Spending</label>
                    <input type="number" step="0.01" id="min_spending" name="min_spending" class="form-control" value="<?= $level['min_spending']; ?>">
                    <?= form_error('min_spending', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        <?= form_close(); ?>
    </div>
</div>