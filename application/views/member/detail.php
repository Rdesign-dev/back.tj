<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Detail Member
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('member') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="<?= base_url('https://terasjapan.com/ImageTerasJapan/ProfPic/' . ($member->profile_pic ?? 'profile.jpg')) ?>" 
                             alt="Profile Picture" class="img-thumbnail mb-3" 
                             width="150" height="150">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td>Nama Member</td>
                                <td>:</td>
                                <td><?= $member->name ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td>Nomor Telepon</td>
                                <td>:</td>
                                <td><?= $member->phone_number ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><?= $member->address ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><?= $member->email ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td><?= $member->gender ? ($member->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Lahir</td>
                                <td>:</td>
                                <td><?= $member->birthdate ? date('d-m-Y', strtotime($member->birthdate)) : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Kota</td>
                                <td>:</td>
                                <td><?= $member->city ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td>Saldo</td>
                                <td>:</td>
                                <td>Rp <?= number_format($member->balance ?? 0, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Poin</td>
                                <td>:</td>
                                <td><?= number_format($member->poin ?? 0, 0, ',', '.') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="card shadow-sm mb-4 border-bottom-primary">
            <div class="card-header bg-white py-3">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Riwayat Transaksi
                </h4>
            </div>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap" id="dataTable">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nama Cabang</th>
                            <th>Nama Kasir</th>
                            <th>Total</th>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($trans as $tr => $tran) :
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tran->kodetransaksi ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($tran->tanggaltransaksi)) ?></td>
                                <td><?= $tran->namacabang ?></td>
                                <td><?= $tran->nama ?></td>
                                <td>Rp <?= number_format($tran->total, 0, ',', '.') ?></td>
                                <td>
                                    <?php 
                                    if (!empty($tran->metodebayar)) {
                                        $payments = explode(" & ", $tran->metodebayar);
                                        foreach($payments as $payment) {
                                            echo '<span class="badge badge-info mr-1">';
                                            if (strpos($payment, 'cash') !== false) {
                                                echo str_replace('cash', 'Cash', $payment);
                                            } else if (strpos($payment, 'transferBank') !== false) {
                                                echo str_replace('transferBank', 'Transfer', $payment);
                                            } else if (strpos($payment, 'Balance') !== false) {
                                                echo str_replace('Balance', 'Saldo', $payment);
                                            }
                                            echo '</span>';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>