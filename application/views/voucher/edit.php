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
                <?= form_open_multipart('voucher/update', [], ['id' => $voucher['id']]); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="title">Judul Voucher</label>
                    <div class="col-md-6">
                        <input type="text" id="title" name="title" value="<?= set_value('title', $voucher['title']); ?>" class="form-control" placeholder="Masukkan Judul Voucher">
                        <?= form_error('title', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="points_required">Poin</label>
                    <div class="col-md-6">
                        <input type="number" id="points_required" name="points_required" value="<?= set_value('points_required', $voucher['points_required']); ?>" class="form-control" placeholder="Masukkan Jumlah Poin">
                        <?= form_error('points_required', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="category">Kategori</label>
                    <div class="col-md-6">
                        <?php
                        $category_options = array(
                            ''          => '-- Pilih Kategori --',
                            'oldmember' => 'Member Biasa',
                            'newmember' => 'Member Baru',
                            'code'      => 'Kode Referal'
                        );
                        echo form_dropdown('category', $category_options, $voucher['category'], 'class="form-control" id="category"');
                        ?>
                        <?= form_error('category', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group" id="quantityRow">
                    <label class="col-md-4 text-md-right" for="qty">Quantity</label>
                    <div class="col-md-6">
                        <input type="number" id="qty" name="qty" value="<?= set_value('qty', $voucher['qty']); ?>" class="form-control" placeholder="Masukkan Quantity">
                        <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="image_name">Foto</label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <img src="https://terasjapan.com/fotovoucher/<?= $voucher['image_name'] ?>" alt="Voucher Image" class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <input type="file" name="image_name" id="image_name">
                                <?= form_error('image_name', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="description">Deskripsi</label>
                    <div class="col-md-6">
                        <textarea class="form-control" id="description" name="description" rows="4"><?= set_value('description', $voucher['description']); ?></textarea>
                        <?= form_error('description', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="valid_until">Berlaku Sampai</label>
                    <div class="col-md-6">
                        <input type="datetime-local" id="valid_until" name="valid_until" value="<?= set_value('valid_until', date('Y-m-d\TH:i', strtotime($voucher['valid_until']))); ?>" class="form-control">
                        <?= form_error('valid_until', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total_days">Total Hari</label>
                    <div class="col-md-6">
                        <input type="number" id="total_days" name="total_days" value="<?= set_value('total_days', $voucher['total_days']); ?>" class="form-control" placeholder="Masukkan Total Hari">
                        <?= form_error('total_days', '<span class="text-danger small">', '</span>'); ?>
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
        // Check category on page load
        toggleQuantityInput();

        // Add listener for category changes
        $("#category").change(function () {
            toggleQuantityInput();
        });

        function toggleQuantityInput() {
            var selectedValue = $("#category").val();
            
            if (selectedValue === "newmember") {
                $("#qty").val(0);
                $("#quantityRow").hide();
            } else {
                $("#quantityRow").show();
            }
        }
    });
</script>