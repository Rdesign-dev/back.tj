<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Ubah Password
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('profilecabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('profilecabang/ubahpassword'); ?>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="password_lama">Password Lama</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                            </div>
                            <input value="<?= set_value('password_lama'); ?>" 
                                   name="password_lama" 
                                   id="password_lama" 
                                   type="password" 
                                   class="form-control" 
                                   placeholder="Password Lama...">
                        </div>
                        <?= form_error('password_lama', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="password_baru">Password Baru</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                            </div>
                            <input value="<?= set_value('password_baru'); ?>" 
                                   name="password_baru" 
                                   id="password_baru" 
                                   type="password" 
                                   class="form-control" 
                                   placeholder="Password Baru...">
                        </div>
                        <?= form_error('password_baru', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="konfirmasi_password">Konfirmasi Password</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                            </div>
                            <input value="<?= set_value('konfirmasi_password'); ?>" 
                                   name="konfirmasi_password" 
                                   id="konfirmasi_password" 
                                   type="password" 
                                   class="form-control" 
                                   placeholder="Konfirmasi Password Baru...">
                        </div>
                        <?= form_error('konfirmasi_password', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>