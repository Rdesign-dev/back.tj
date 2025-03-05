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
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?php echo form_open_multipart('undian/inputPoin'); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="poin">Poin Handphone</label>
                    <div class="col-md-6">
                        <input value="<?= set_value('poin'); ?>" type="text" id="poin" name="poin" class="form-control" placeholder="Masukkan Poin">
                        <?= form_error('poin', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="gambar">Foto Undian</label>
                    <div class="col-md-6">
                        <input type="file" id="gambar" name="gambar" class="form-control" placeholder="gambar">
                        <?= form_error('gambar', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>

                <br>
                <div class="row form-group justify-content-end">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon"><i class="fas fa-save"></i></span>
                            <span class="text">Simpan</span>
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Undian
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Poin</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($undians) :
                    foreach ($undians as $undian) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $undian['poin']; ?></td>
                            <td><img src="https://terasjapan.com/fotoundian/<?= $undian['gambar'] ?>" alt="" width="100px" height="100px"></td>
                            <td><a href="<?= base_url('undian/edit_undian/') . $undian['id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                            <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('undian/delete/') . $undian['id'] ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data undian</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>