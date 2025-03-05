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
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?php echo form_open('bantuan/update_message'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="content">Pesan OTP Wa</label>
                    <div class="col-md-6">
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <textarea name="konten" id="konten" rows ="4" cols="50"><?= $currentMessage->content ?></textarea>
                        <?= form_error('content', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
