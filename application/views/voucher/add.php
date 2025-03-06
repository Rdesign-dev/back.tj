
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
                <?php echo form_open_multipart('voucher/tambah_save'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="kodevoucher">Kode Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('kodevoucher'); ?>" type="text" id="kodevoucher" name="kodevoucher" class="form-control" placeholder="Masukkan Kode Voucher">
                        <?= form_error('kodevoucher', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="namavoucher">Nama Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('namavoucher'); ?>" type="text" id="namavoucher" name="namavoucher" class="form-control" placeholder="Masukkan Nama Voucher">
                        <?= form_error('namavoucher', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="poin">Poin Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('poin'); ?>" type="number" id="poin" name="poin" class="form-control" placeholder="Masukkan Poin">
                        <?= form_error('poin', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="kategori">Kategori</label>
                    <div class="col-md-6">
                        <select id="kategori" name="kategori" class="form-control">
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            <option value="memberbiasa">Member Biasa</option>
                            <option value="memberbaru">Member Baru</option>
                            <option value="kodereferal">Kode Referal</option>
                        </select>
                        <?= form_error('kategori', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group" id="quantityRow" style="display:none;">
                    <label class="col-md-4 text-md-right" for="quantity">Quantity</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('quantity'); ?>" type="number" id="quantity" name="quantity" class="form-control" placeholder="Masukkan Quantity">
                        <?= form_error('quantity', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="foto">Foto Voucher</label>
                    <div class="col-md-6">
                        <input type="file" id="foto" name="foto" class="form-control" placeholder="foto">
                        <?= form_error('foto', '<span class="text-danger small">', '</span>'); ?>
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

            if (selectedValue === "memberbiasa") {
                // Jika kategori "Member Biasa" dipilih, tampilkan input quantity
                $("#quantityRow").show();
            } else {
                // Jika kategori lain dipilih, sembunyikan input quantity
                $("#quantityRow").hide();
            }
        }
    });
</script>

