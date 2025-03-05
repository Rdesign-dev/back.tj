<div class="row justify-content-center mt-3 pt-lg-5">
    <div class="col-xl-6 col-lg-6 col-md-0">
        <div class="card border-0" style="height: 500px; background-color: rgba(255, 255, 255, 0);">
            <div class="card-body p-lg-5 p-3 shadow-lm">
                <div class="row">
                    <!-- Menambahkan gambar di sini -->
                    <div class="col-lg-12 text-center mb-4">
                        <img src="<?= base_url('assets/img/logo.PNG')?>" alt="Your Image Alt Text" style="max-width: 100%; max-height: 200px;">
                    </div>

                    <div class="col-lg-12 text-center mb-4">
                        <h1 class="h3" style="font-family: 'Noto Sans JP'; color: black;">LOGIN ADMIN</h1>
                    </div>

                    <?php if (!empty($error)) : ?>
                        <div class="col-lg-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $error ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12">
                            <?= $this->session->flashdata('pesan'); ?>
                            <?= form_open('auth/login', ['class' => 'user']); ?>
                            <div class="form-group">
                                <input autofocus="autofocus" autocomplete="off" value="<?= set_value('username'); ?>" type="text" name="username" class="form-control form-control-user" placeholder="Username">
                                <?= form_error('username', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-user" placeholder="Password">
                                <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            <button type="submit" style="background-color: black; color: white; font-weight: bold;" class="btn btn-user btn-block">
                                Login
                            </button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
