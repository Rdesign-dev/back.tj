<!-- filepath: c:\laragon\www\back.tj\application\views\mission\edit.php -->
<div class="container mt-4">
    <h4 class="mb-3">Edit Mission</h4>
    <form method="post">
        <div class="form-group mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= set_value('title', $mission['title']); ?>">
            <?= form_error('title', '<small class="text-danger">', '</small>'); ?>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?= set_value('description', $mission['description']); ?></textarea>
            <?= form_error('description', '<small class="text-danger">', '</small>'); ?>
        </div>
        <div class="form-group mb-3">
            <label for="point_reward">Point Reward</label>
            <input type="number" name="point_reward" id="point_reward" class="form-control" value="<?= set_value('point_reward', $mission['point_reward']); ?>">
            <?= form_error('point_reward', '<small class="text-danger">', '</small>'); ?>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('mission'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>