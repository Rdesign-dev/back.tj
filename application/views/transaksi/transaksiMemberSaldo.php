
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
                        <a href="<?= base_url('transaksi/saldo') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('transaksi/convert_and_updateSaldoMember'); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nominal">Nominal TopUp</label>
                    <div class="col-md-6">
                        <input type="text" id="nominal" name="nominal" class="form-control">
                        <?= form_error('nominal', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="metode">Metode Pembayaran</label>
                    <div class="col-md-6">
                        <select name="metode" id="metode" class="form-control">
                            <option value="">- Pilih Metode Pembayaran -</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qrisbca">Qris BCA</option>
                            <option value="qris">Qris Non BCA</option>
                        </select>
                        <?= form_error('metode', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="bukti">Bukti Pembayaran</label>
                    <div class="col-md-6">
                        <input type="file" id="bukti" name="bukti" class="form-control">
                        <?= form_error('bukti', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nomor">Nomor Member</label>
                    <div class="col-md-6">
                        <?php if(isset($member)){
                            foreach($member as $mb => $data){
                                ?>
                                <input type="text" name="nomor" id="nomor" class="form-control" value="<?=$data['nomor']?>" readonly>
                                <?php
                            }
                        }
                        ?>
                        
                        <?= form_error('nomor', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="nama">Nama Member</label>
                    <div class="col-md-6">
                        <?php if(isset($member)){
                            foreach($member as $mb => $data){
                                ?>
                                <input type="text" name="nama" class="form-control" id="nama" value="<?=$data['namamember']?>" readonly>
                                <?php
                            }
                        }
                        ?>
                        
                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-save"></i></span>
                            <span class="text">Simpan</span>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.querySelector('form');

        form.addEventListener("submit", function (event) {
            var nominalInput = document.getElementById('nominal');
            var metodeInput = document.getElementById('metode');
            var buktiInput = document.getElementById('bukti');
            var nomorInput = document.getElementById('nomor');
            var namaInput = document.getElementById('nama');

            if (!nominalInput.value.trim()) {
                event.preventDefault();
                nominalInput.classList.add("is-invalid");
            } else {
                nominalInput.classList.remove("is-invalid");
            }

            if (!metodeInput.value.trim()) {
                event.preventDefault();
                metodeInput.classList.add("is-invalid");
            } else {
                metodeInput.classList.remove("is-invalid");
            }

            if (!buktiInput.value.trim()) {
                event.preventDefault();
                buktiInput.classList.add("is-invalid");
            } else {
                buktiInput.classList.remove("is-invalid");
            }

            if (!nomorInput.value.trim()) {
                event.preventDefault();
                nomorInput.classList.add("is-invalid");
            } else {
                nomorInput.classList.remove("is-invalid");
            }

            if (!namaInput.value.trim()) {
                event.preventDefault();
                namaInput.classList.add("is-invalid");
            } else {
                namaInput.classList.remove("is-invalid");
            }
        });
    });
</script>




