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
                        <a href="<?= base_url('bantuan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('',[], ['id' => $bantuan['id']]); ?>
                <!-- Tambahkan input hidden untuk menyimpan ID member -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="judul">Kode Voucher</label>
                    <div class="col-md-6">
                    <?php if ($bantuan) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <input type="text" id="judul" name="judul" value="<?= set_value('judul', $bantuan['judul']); ?>" class="form-control" placeholder="Masukkan Judul">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="isi">Ketentuan Pemakaian</label>
                    <div class="col-md-12">
                    <?php if ($bantuan) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <textarea name="isi" id="isi" value="<?= set_value('isi', $bantuan['isi']); ?>"><?= $bantuan['isi'] ?></textarea>
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tags">Kategori</label>
                    <div class="col-md-6">
                        <?php if ($bantuan) : ?>
                            <?php
                            $kategori_options = array(
                                ''          => '-- Pilih Kategori --',
                                'membership'=> 'Membership',
                                'voucher'   => 'Voucher',
                                'poin'      => 'Poin',
                                'topup'     => 'Top Up'
                            );
                            echo form_dropdown('tags', $kategori_options, $bantuan['tags'], 'class="form-control" id="tags"');
                            ?>
                            <?= form_error('tags', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Update</span>
                        </button>
                        <button type="reset" class="btn btn-secondary btn-icon-split">
                        <span class="icon"><i class="fas fa-backspace"></i></span>
                            <span class="text">Reset</span>
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
