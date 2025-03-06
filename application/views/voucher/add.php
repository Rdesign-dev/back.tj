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
                    <label class="col-md-4 text-md-right" for="title">Nama Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('title'); ?>" type="text" id="title" name="title" class="form-control" placeholder="Masukkan Nama Voucher">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="category">Kategori</label>
                    <div class="col-md-6">
                        <select id="category" name="category" class="form-control">
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            <option value="oldmember">Member Biasa</option>
                            <option value="newmember">Member Baru</option>
                            <option value="code">Kode Referal</option>
                        </select>
                        <?= form_error('category', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group" id="pointsRow">
                    <label class="col-md-4 text-md-right" for="points_required">Poin Voucher</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('points_required'); ?>" type="number" id="points_required" name="points_required" class="form-control" placeholder="Masukkan Poin">
                        <?= form_error('points_required', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea id="description" name="description" class="form-control" placeholder="Masukkan Deskripsi"><?= set_value('description'); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group" id="validUntilRow">
                    <label class="col-md-4 text-md-right" for="valid_until">Berlaku Sampai</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('valid_until'); ?>" type="date" id="valid_until" name="valid_until" class="form-control">
                        <?= form_error('valid_until', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group" id="totalDaysRow">
                    <label class="col-md-4 text-md-right" for="total_days">Total Hari</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('total_days', '30'); ?>" type="number" id="total_days" name="total_days" class="form-control" placeholder="Masukkan Total Hari">
                        <?= form_error('total_days', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group" id="qtyRow">
                    <label class="col-md-4 text-md-right" for="qty">Quantity</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('qty'); ?>" type="number" id="qty" name="qty" class="form-control" placeholder="Masukkan Quantity">
                        <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image_name">Foto Voucher</label>
                    <div class="col-md-6">
                        <input type="file" id="image_name" name="image_name" class="form-control">
                        <?= form_error('image_name', '<span class="text-danger small">', '</span>'); ?>
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
        // Toggle fields based on category selection
        function toggleFields() {
            var selectedCategory = $("#category").val();
            
            if (selectedCategory === "newmember") {
                // For new members, hide points, valid_until, total_days, and qty
                $("#pointsRow").hide();
                $("#validUntilRow").hide();
                $("#totalDaysRow").hide();
                $("#qtyRow").hide();
                
                // Set default values
                $("#points_required").val(0);
                $("#valid_until").val("");
                $("#total_days").val(0);
                $("#qty").val(0);
            } else {
                // For other categories, show all fields
                $("#pointsRow").show();
                $("#validUntilRow").show();
                $("#totalDaysRow").show();
                $("#qtyRow").show();
            }
        }
        
        // Run on page load
        toggleFields();
        
        // Run when category changes
        $("#category").change(function() {
            toggleFields();
        });
        
        // Set default date for valid_until (30 days from now)
        var today = new Date();
        today.setDate(today.getDate() + 30);
        var defaultDate = today.toISOString().split('T')[0];
        $("#valid_until").val(defaultDate);
    });
</script>