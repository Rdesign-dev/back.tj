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
                        <a href="<?= base_url('brand') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('brand/save', array('id' => 'brandForm')); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="name">Nama Promo </label>
                    <div class="col-md-6">
                        <input value="<?= set_value('name'); ?>" type="text" id="name" name="name" class="form-control" placeholder="Masukkan Nama Brand">
                        <?= form_error('name', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="desc">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="desc" name="desc" class="form-control" placeholder="Masukkan Deskripsi Brand"><?= set_value('desc'); ?></textarea>
                        <?= form_error('desc', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="desc">Status</label>
                    <div class="col-md-6">
                        <textarea id="desc" name="desc" class="form-control" placeholder="Masukkan Deskripsi Brand"><?= set_value('desc'); ?></textarea>
                        <?= form_error('desc', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="desc">Batas Waktu</label>
                    <div class="col-md-6">
                        <textarea id="desc" name="desc" class="form-control" placeholder="Masukkan Deskripsi Brand"><?= set_value('desc'); ?></textarea>
                        <?= form_error('desc', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image">Gambar</label>
                    <div class="col-md-6">
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        <?= form_error('image', '<div class="text-danger small">', '</div>'); ?>
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('brandForm');
    const requiredFields = ['name', 'desc', 'image', 'banner'];
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>

