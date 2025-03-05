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
                        <a href="<?= base_url('voucher') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open_multipart('',[], ['kodevoucher' => $voucher['kodevoucher']]); ?>
                <!-- Tambahkan input hidden untuk menyimpan ID member -->
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="kodevoucher">Kode Voucher</label>
                    <div class="col-md-6">
                    <?php if ($voucher) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <input type="text" id="kodevoucher" name="kodevoucher" value="<?= set_value('kodevoucher', $voucher['kodevoucher']); ?>" class="form-control" placeholder="Masukkan kodevoucher Promosi">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="namavoucher">Nama Voucher</label>
                    <div class="col-md-6">
                    <?php if ($voucher) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <input type="text" id="namavoucher" name="namavoucher" value="<?= set_value('namavoucher', $voucher['namavoucher']); ?>" class="form-control" placeholder="Masukkan Nama Voucher">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="poin">Kode Voucher</label>
                    <div class="col-md-6">
                    <?php if ($voucher) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <input type="number" id="poin" name="poin" value="<?= set_value('poin', $voucher['poin']); ?>" class="form-control" placeholder="Masukkan Poin Voucher">
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="kategori">Kategori</label>
                    <div class="col-md-6">
                        <?php if ($voucher) : ?>
                            <?php
                            $kategori_options = array(
                                ''          => '-- Pilih Kategori --',
                                'memberbiasa'     => 'Member Biasa',
                                'memberbaru'     => 'Member Baru',
                                'kodereferal'   => 'Kode Referal'
                            );
                            echo form_dropdown('kategori', $kategori_options, $voucher['isNew'], 'class="form-control" id="kategori"');
                            ?>
                            <?= form_error('kategori', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group" id="quantityRow" style="display:none;">
                    <label class="col-md-4 text-md-right" for="quantity">Quantity</label>
                    <div class="col-md-6">
                    <?php if ($voucher) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <input type="number" id="quantity" name="quantity" value="<?= set_value('quantity', $voucher['qty']); ?>" class="form-control" placeholder="Masukkan quantity Voucher">
                        <?= form_error('quantity', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="foto">Foto</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                            <?php if ($voucher) : ?>
                                <img src="https://terasjapan.com/fotovoucher/<?= $voucher['foto'] ?>" alt="User" class="rounded-circle shadow-sm img-thumbnail">
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <input type="file" name="foto" id="foto">
                                <?= form_error('foto', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="syarat">Ketentuan Penukaran</label>
                    <div class="col-md-12">
                    <?php if ($voucher) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <textarea name="syarat" id="syarat"  value="<?= set_value('syarat', $voucher['syarat']); ?>"><?= $voucher['syarat'] ?></textarea>
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="syarattukar">Ketentuan Pemakaian</label>
                    <div class="col-md-12">
                    <?php if ($voucher) : ?>
                                <!-- Tampilkan nilai yang akan di-edit -->
                        <textarea name="syarattukar" id="syarattukar" value="<?= set_value('syarattukar', $voucher['syarattukar']); ?>"><?= $voucher['syarattukar'] ?></textarea>
                        <?= form_error('link', '<span class="text-danger small">', '</span>'); ?>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Pada saat halaman dimuat, cek nilai kategori dan tampilkan/hide input quantity
        toggleQuantityInput();

        // Tambahkan event listener untuk perubahan nilai pada dropdown kategori
        $("#kategori").change(function () {
            // Simpan nilai kategori ke sessionStorage
            sessionStorage.setItem('selectedCategory', $("#kategori").val());
            toggleQuantityInput();
        });

        function toggleQuantityInput() {
            var selectedValue = sessionStorage.getItem('selectedCategory') || $("#kategori").val();
            
            // Set nilai kategori berdasarkan yang tersimpan di sessionStorage atau nilai saat ini jika sessionStorage kosong
            $("#kategori").val(selectedValue);

            if (selectedValue === "0") {
                // Jika kategori "Member Biasa" dipilih, tampilkan input quantity
                $("#quantityRow").show();
            } else {
                // Jika kategori lain dipilih, sembunyikan input quantity
                $("#quantityRow").hide();
            }
        }
    });
</script>