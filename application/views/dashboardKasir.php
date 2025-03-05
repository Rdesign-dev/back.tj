<div class="row">
    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Data Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $transaksi; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-history fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyTransactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('monthlyTransactionChart').getContext('2d');
    var monthlyTransactionData = <?= $monthlyTransactionData; ?>;

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyTransactionData.map(entry => entry.month),
            datasets: [{
                label: 'Transaksi Bulanan',
                data: monthlyTransactionData.map(entry => entry.count),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>


