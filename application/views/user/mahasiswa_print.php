<style>
  body { font-family: Arial, Helvetica, sans-serif; color: #343a40; }
  h2 { margin: 0 0 6px; font-size: 22px; text-align: center; }
  .subtitle { text-align: center; font-size: 12px; color: #6c757d; margin-bottom: 12px; }
  table.print-table { width: 100%; border-collapse: collapse; font-size: 14px; }
  .print-table th, .print-table td { padding: 8px 10px; border: 1px solid #dee2e6; vertical-align: middle; }
  .print-table th { background: #ffffff; text-align: left; }
  .footer { text-align: center; margin-top: 18px; font-size: 12px; color: #6c757d; }
  @media print {
    body * { visibility: hidden; }
    .print-only, .print-only * { visibility: visible; }
    .print-only { position: absolute; left: 0; top: 0; width: 100%; padding: 0 8px; }
    #mahasiswatable, .dataTables_wrapper { display: none !important; }
  }
  @media screen { .print-only { display: none; } }
</style>
<div class="print-only">
  <h2>Data Mahasiswa</h2>
  <div class="subtitle">
    Dicetak: <?= htmlspecialchars($printed_at ?? '') ?> | Filter Jenis Kelamin: <?= htmlspecialchars($filter_jk_label ?? 'Semua') ?>
  </div>
  <table class="print-table">
    <thead>
      <tr>
        <th style="width:50px; text-align:center;">#</th>
        <th>Nama</th>
        <th>NIM</th>
        <th>Prodi</th>
        <th>Jenis Kelamin</th>
        <th>Image</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($mahasiswa)) : ?>
        <?php $i = 1; foreach ($mahasiswa as $m) : ?>
          <tr>
            <td style="text-align:center;"><?= $i++; ?></td>
            <td><?= htmlspecialchars($m['nama_siswa'] ?? '') ?></td>
            <td><?= htmlspecialchars($m['nim'] ?? '') ?></td>
            <td><?= htmlspecialchars($m['prodi_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($m['jenis_kelamin_label'] ?? '') ?></td>
            <td style="text-align:center;">
              <img
                src="<?= base_url('assets/img/profile/') . ($m['image'] ?? 'default.jpg') ?>"
                width="45"
                height="45"
                style="object-fit:cover;border-radius:4px;"
                onerror="this.onerror=null;this.src='<?= base_url('assets/img/profile/default.jpg') ?>';"
              >
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="6" style="text-align:center;">Tidak ada data.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <div class="footer">Copyright © Web programing UNPAS 2026</div>
</div>
