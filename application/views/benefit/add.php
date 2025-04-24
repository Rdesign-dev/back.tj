<!-- filepath: c:\laragon\www\back.tj\application\views\benefit\add.php -->
<div class="container mt-4">
    <h4 class="mb-3">Tambah Benefit</h4>
    <form method="post">
        <div class="form-group mb-3">
            <label for="level_id">Level</label>
            <select name="level_id" id="level_id" class="form-control">
                <option value="">-- Pilih Level --</option>
                <?php foreach ($levels as $level): ?>
                    <option value="<?= $level['id']; ?>" <?= set_select('level_id', $level['id']); ?>>
                        <?= $level['level_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?= form_error('level_id', '<small class="text-danger">', '</small>'); ?>
        </div>
        <div class="form-group mb-3">
            <label for="benefit_title">Benefit Title</label>
            <input type="text" name="benefit_title" id="benefit_title" class="form-control" value="<?= set_value('benefit_title'); ?>">
            <?= form_error('benefit_title', '<small class="text-danger">', '</small>'); ?>
        </div>
        <div class="form-group mb-3">
            <label for="benefit_description">Benefit Description</label>
            <textarea name="benefit_description" id="benefit_description" class="form-control" rows="4"><?= set_value('benefit_description'); ?></textarea>
            <?= form_error('benefit_description', '<small class="text-danger">', '</small>'); ?>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('benefit'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>