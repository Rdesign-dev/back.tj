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
                        <a href="<?= base_url('banner') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('banner/save', array('id' => 'bannerForm')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Nama Banner</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('title'); ?>" type="text" id="title" name="title" class="form-control" placeholder="Masukkan Nama Banner">
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="link">Link Banner</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('link'); ?>" type="text" id="link" name="link" class="form-control" placeholder="Masukkan Link Banner">
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar Banner</label>
                    <div class="col-md-6">
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="status">Status</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control">
                            <option value="Active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var bannerForm = document.getElementById('bannerForm');
    var titleInput = document.getElementById('title');
    var linkInput = document.getElementById('link');
    var imageInput = document.getElementById('image');

    bannerForm.addEventListener("submit", function (event) {
        let isValid = true;

        if (!titleInput.value.trim()) {
            event.preventDefault();
            titleInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!imageInput.value.trim()) {
            event.preventDefault();
            imageInput.classList.add("is-invalid");
            isValid = false;
        }

        return isValid;
    });

    [titleInput, linkInput, imageInput].forEach(input => {
        input.addEventListener("input", function () {
            this.classList.remove("is-invalid");
        });
    });
});
</script>