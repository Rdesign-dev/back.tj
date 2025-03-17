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
                        <a href="<?= base_url('content') ?>" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open_multipart('', [], ['id' => $content['id']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="name">Name</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('name', $content['name']); ?>" type="text" id="name" name="name" class="form-control" placeholder="Content Name">
                        <?= form_error('name', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="Image">Image</label>
                    <div class="col-md-6">
                        <input type="file" id="Image" name="Image" class="form-control">
                        <small class="form-text text-muted">Current image: <?= $content['Image']; ?></small>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="link">Link</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('link', $content['link']); ?>" type="text" id="link" name="link" class="form-control" placeholder="Content Link">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Save</span>
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