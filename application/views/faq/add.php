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
                        <a href="<?= base_url('faq') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('faq/add'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="question">Pertanyaan</label>
                    <div class="col-md-6">
                        <textarea id="question" name="question" class="form-control" rows="3"><?= set_value('question'); ?></textarea>
                        <?= form_error('question', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="answer">Jawaban</label>
                    <div class="col-md-6">
                        <textarea id="answer" name="answer" class="form-control" rows="5"><?= set_value('answer'); ?></textarea>
                        <?= form_error('answer', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="status">Status</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control">
                            <option value="Active" <?= set_select('status', 'Active', TRUE); ?>>Active</option>
                            <option value="inactive" <?= set_select('status', 'inactive'); ?>>Inactive</option>
                        </select>
                        <?= form_error('status', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <br>
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