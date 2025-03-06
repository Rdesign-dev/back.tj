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
                    <th>Total Hari</th>
                    <th>Qty</th>
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
                            <td><img src="https://terasjapan.com/fotovoucher/<?= $voucher['image_name'] ?>" alt="" width="150px" height="100px"></td>
                            <td><?= $voucher['points_required']; ?></td>
                            <td>
                                <?php
                                if ($voucher['category'] == 'oldmember') {
                                    echo "Member Biasa";
                                } elseif ($voucher['category'] == 'newmember') {
                                    echo "Member Baru";
                                } elseif($voucher['category'] == 'code'){
                                    echo "Kode Referal";
                                } else {
                                    echo "Undefined";
                                }
                                ?>
                            </td>
                            <td><?= $voucher['description']; ?></td>
                            <td><?= date('d-m-Y', strtotime($voucher['valid_until'])); ?></td>
                            <td><?= $voucher['total_days']; ?></td>
                            <td><?= $voucher['qty']; ?></td>
                            <td>
                                <a href="<?= base_url('voucher/edit_voucher/') . $voucher['id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                                <a onclick="return confirm('Yakin ingin menghapus data?')" href="<?= base_url('voucher/delete/') . $voucher['id'] ?>" class="btn btn-circle btn-sm btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else : ?>
                    <tr>
                        <td colspan="10" class="text-center">Silahkan tambahkan Voucher Penukaran Poin</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>