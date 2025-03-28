<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm mb-4 border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Riwayat Transaksi
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('transaksi') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">
                        Tambah Transaksi
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Tipe Transaksi</th>
                    <th>Nama Cabang</th>
                    <th>Nama Member</th>
                    <th>Nama Kasir</th>
                    <th class="column-payment">Total</th>
                    <th class="column-payment">Metode Pembayaran</th>
                    <th class="column-voucher">Kode Voucher (Redeem)</th>
                    <th>Foto Bill</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($trans as $tr => $tran) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $tran->transaction_codes ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($tran->created_at)) ?></td>
                        <td>
                            <?php 
                            $badge_class = $tran->transaction_type == 'Teras Japan Payment' ? 'badge-primary' : 'badge-success';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= $tran->transaction_type ?></span>
                        </td>
                        <td><?= $tran->branch_name ?? '-' ?></td>
                        <td><?= $tran->member_name ?></td>
                        <td><?= $tran->cashier_name ?? '-' ?></td>
                        <td class="column-payment">
                            <?php if($tran->transaction_type == 'Teras Japan Payment'): ?>
                                Rp <?= number_format($tran->amount, 0, ',', '.') ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="column-payment"><?= $tran->payment_method ?? '-' ?></td>
                        <td class="column-voucher">
                            <?php if($tran->transaction_type == 'Redeem Voucher'): ?>  <!-- Perbaiki penulisan 'Redeem' -->
                                <span class="badge badge-info"><?= $tran->kode_voucher ?? 'No Code' ?></span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($tran->transaction_evidence && $tran->transaction_evidence != 'struk.png'): ?>
                                <img src="<?= base_url('../ImageTerasJapan/transaction_proof/' . $tran->transaction_evidence) ?>" 
                                     alt="Bill" width="150px" height="100px">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#dataTable tbody tr');
    
    rows.forEach(row => {
        const typeCell = row.querySelector('td:nth-child(4)');
        const transactionType = typeCell.textContent.trim();
        
        if (transactionType === 'Reedem Voucher') {
            // Hide payment columns
            row.querySelectorAll('.column-payment').forEach(cell => {
                cell.style.display = 'none';
            });
            // Show voucher column
            row.querySelectorAll('.column-voucher').forEach(cell => {
                cell.style.display = '';
            });
        } else if (transactionType === 'Teras Japan Payment') {
            // Show payment columns
            row.querySelectorAll('.column-payment').forEach(cell => {
                cell.style.display = '';
            });
            // Hide voucher column
            row.querySelectorAll('.column-voucher').forEach(cell => {
                cell.style.display = 'none';
            });
        }
    });

    // Handle header columns
    const firstRow = document.querySelector('#dataTable tbody tr');
    if (firstRow) {
        const typeCell = firstRow.querySelector('td:nth-child(4)');
        const transactionType = typeCell.textContent.trim();
        
        document.querySelectorAll('#dataTable thead th.column-payment').forEach(th => {
            th.style.display = transactionType === 'Reedem Voucher' ? 'none' : '';
        });
        
        document.querySelectorAll('#dataTable thead th.column-voucher').forEach(th => {
            th.style.display = transactionType === 'Teras Japan Payment' ? 'none' : '';
        });
    }
});
</script>