<!-- filepath: c:\laragon\www\back.tj\application\views\benefit\edit.php -->
<div class="container mt-4">
    <h4 class="mb-3">Edit Benefit</h4>
    <form method="post">
        <div class="form-group mb-3">
            <label for="benefit_title">Benefit Title</label>
            <input type="text" name="benefit_title" id="benefit_title" class="form-control" value="<?= set_value('benefit_title', $benefit['benefit_title']); ?>">
            <?= form_error('benefit_title', '<small class="text-danger">', '</small>'); ?>
        </div>
        <div class="form-group mb-3">
            <label for="benefit_description">Benefit Description</label>
            <textarea name="benefit_description" id="benefit_description" class="form-control" rows="4"><?= set_value('benefit_description', $benefit['benefit_description']); ?></textarea>
            <?= form_error('benefit_description', '<small class="text-danger">', '</small>'); ?>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('benefit'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>