<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Voucher Poin
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('voucher/tambahs') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Voucher
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th>Poin</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Berlaku Sampai</th>
                    <th>Total Hari Aktif</th>
                    <th>Qty</th>
                    <th>Brand</th> <!-- New column -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($vouchers) :
                    foreach ($vouchers as $voucher) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $voucher['title']; ?></td>
                            <td>
                                <?php if(!empty($voucher['image_name'])): ?>
                                    <img src="https://terasjapan.com/ImageTerasJapan/reward/<?= $voucher['image_name'] ?>"
                                         alt="Voucher Image" 
                                         width="150px" 
                                         height="100px"
                                         class="img-thumbnail">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $voucher['points_required']; ?></td>
                            <td><?= ucfirst($voucher['category']); ?></td>
                            <td><?= $voucher['description']; ?></td>
                            <td><?= date('d-m-Y', strtotime($voucher['valid_until'])); ?></td>
                            <td><?= $voucher['total_days']; ?></td>
                            <td><?= $voucher['qty']; ?></td>
                            <td><?= $voucher['brand_name']; ?></td> <!-- Display brand name -->
                            <td>
                                <a href="<?= base_url('voucher/edit_voucher/') . $voucher['id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                                <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('voucher/delete/') . $voucher['id'] ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="11" class="text-center">Silahkan tambahkan Voucher Penukaran Poin</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>