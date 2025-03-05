<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form <?= $title; ?>
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('blog') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Kembali
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?php echo form_open_multipart('blog/tambah_save', array('id' => 'newsEventForm')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Judul</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('title'); ?>" type="text" id="title" name="title" 
                               class="form-control" placeholder="Masukkan Judul">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="captions">Caption</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('captions'); ?>" type="text" id="captions" 
                               name="captions" class="form-control" placeholder="Masukkan Caption">
                        <?= form_error('captions', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="description" name="description" class="form-control" 
                                rows="4" placeholder="Masukkan Deskripsi"><?= set_value('description'); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar</label>
                    <div class="col-md-6">
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Format: jpg, jpeg, png, gif (Max: 2MB)</small>
                        <?= form_error('image', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            Reset
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById('newsEventForm');
    var titleInput = document.getElementById('title');
    var captionsInput = document.getElementById('captions');
    var descriptionInput = document.getElementById('description');
    var imageInput = document.getElementById('image');

    form.addEventListener("submit", function (event) {
        let isValid = true;

        if (!titleInput.value.trim()) {
            event.preventDefault();
            titleInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!captionsInput.value.trim()) {
            event.preventDefault();
            captionsInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!descriptionInput.value.trim()) {
            event.preventDefault();
            descriptionInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!imageInput.value && !imageInput.getAttribute('data-existing')) {
            event.preventDefault();
            imageInput.classList.add("is-invalid");
            isValid = false;
        }

        return isValid;
    });

    [titleInput, captionsInput, descriptionInput, imageInput].forEach(input => {
        input.addEventListener("input", function () {
            this.classList.remove("is-invalid");
        });
    });
});
</script>