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
                        <a href="<?= base_url('iklan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('iklan/tambah_save', array('id' => 'memberForm')); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Nama Brand</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('title'); ?>" type="text" id="title" name="title" class="form-control" placeholder="Masukkan Nama Promo">
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="description" name="description" class="form-control" placeholder="Masukkan Deskripsi Promo"><?= set_value('description'); ?></textarea>
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="foto">Gambar Logo</label>
                    <div class="col-md-6">
                        <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                        <div id="error-message" class="text-danger small"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="foto">Gambar Banner</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <img src="<?= base_url('../ImageTerasJapan/promo/' . $iklan['image_name']) ?>" alt="Promo Image" class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                <?= form_error('foto', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Link Instagram</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $iklan['title']); ?>" class="form-control" placeholder="Masukkan Nama Promo">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Link Tiktok</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $iklan['title']); ?>" class="form-control" placeholder="Masukkan Nama Promo">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Link WA</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $iklan['title']); ?>" class="form-control" placeholder="Masukkan Nama Promo">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Link Web</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $iklan['title']); ?>" class="form-control" placeholder="Masukkan Nama Promo">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
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
    var memberForm = document.getElementById('memberForm');
    var titleInput = document.getElementById('title');
    var descInput = document.getElementById('description');
    var fotoInput = document.getElementById('foto');

    memberForm.addEventListener("submit", function (event) {
        let isValid = true;

        if (!titleInput.value.trim()) {
            event.preventDefault();
            titleInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!descInput.value.trim()) {
            event.preventDefault();
            descInput.classList.add("is-invalid");
            isValid = false;
        }

        if (!fotoInput.value.trim()) {
            event.preventDefault();
            fotoInput.classList.add("is-invalid");
            isValid = false;
        }

        return isValid;
    });

    [titleInput, descInput, fotoInput].forEach(input => {
        input.addEventListener("input", function () {
            this.classList.remove("is-invalid");
        });
    });
});
</script>

