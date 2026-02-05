<!-- Begin Page Content -->
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?= htmlspecialchars($title ?? 'Chart Mahasiswa') ?></h1>

  <?php $filter = isset($filter_jk) ? $filter_jk : ''; ?>

  <div class="mb-3">
    <a class="btn btn-secondary" href="<?= site_url('user/mahasiswa') . ($filter ? ('?jenis_kelamin=' . urlencode($filter)) : '') ?>">Kembali ke Mahasiswa</a>
    <a class="btn btn-light" href="<?= site_url('user/mahasiswa_chart') ?>">Semua</a>
    <a class="btn btn-light" href="<?= site_url('user/mahasiswa_chart?jenis_kelamin=L') ?>">Laki-laki saja</a>
    <a class="btn btn-light" href="<?= site_url('user/mahasiswa_chart?jenis_kelamin=P') ?>">Perempuan saja</a>
  </div>

  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Distribusi Jenis Kelamin</h5>
      <canvas id="jkChart" height="140"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  var el = document.getElementById('jkChart');
  if (!el || typeof Chart === 'undefined') return;
  var counts = { L: <?= (int)($gender_counts['L'] ?? 0) ?>, P: <?= (int)($gender_counts['P'] ?? 0) ?> };
  var ctx = el.getContext('2d');
  var data = {
    labels: ['Laki-laki', 'Perempuan'],
    datasets: [
      {
        label: 'Laki-laki',
        data: [counts.L, 0],
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgb(54, 162, 235)',
        borderWidth: 2,
        borderRadius: 10,
        borderSkipped: false
      },
      {
        label: 'Perempuan',
        data: [0, counts.P],
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
        borderColor: 'rgb(255, 99, 132)',
        borderWidth: 2,
        borderRadius: Number.MAX_VALUE,
        borderSkipped: false
      }
    ]
  };
  var config = {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
      plugins: { legend: { display: true, position: 'top' }, title: { display: true, text: 'Distribusi Jenis Kelamin' } }
    }
  };
  new Chart(ctx, config);
})();
</script>
