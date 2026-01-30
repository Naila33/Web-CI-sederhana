<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>


<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newMahasiswaModal">Tambah mahasiswa</a>

<form method="get" action="<?= site_url('user/mahasiswa'); ?>" class="form-inline mb-3">
  <label for="filter_jk" class="mr-2">Filter Jenis Kelamin</label>
  <select name="jenis_kelamin" id="filter_jk" class="form-control mr-2">
    <option value="">Semua</option>
    <option value="L" <?= isset($filter_jk) && $filter_jk === 'L' ? 'selected' : '' ?>>Laki-laki</option>
    <option value="P" <?= isset($filter_jk) && $filter_jk === 'P' ? 'selected' : '' ?>>Perempuan</option>
  </select>
  <?php if (!empty($filter_jk)) : ?>
    <a href="<?= site_url('user/mahasiswa'); ?>" class="btn btn-light ml-2">Reset</a>
  <?php endif; ?>
</form>

<button class="btn btn-success" onclick="window.print()">
  Print
</button>



<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="mahasiswatable" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama mahasiswa</th>
        <th scope="col">Nim</th>
        <th scope="col">Prodi </th>
        <th scope="col">Jenis kelamin</th>
        <th scope="col">Image</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($mahasiswa)) : ?>
        <?php $i = 1; foreach ($mahasiswa as $m) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= htmlspecialchars($m['nama_siswa'] ?? ''); ?></td>
            <td><?= htmlspecialchars($m['nim'] ?? ''); ?></td>
            <td><?= htmlspecialchars($m['prodi_name'] ?? ''); ?></td>
            <td><?= htmlspecialchars($m['jenis_kelamin_label'] ?? ''); ?></td>
            <td><img src="<?= base_url('assets/img/profile/').($m['image'] ?? 'default.jpg') ?>" class="img-thumbnail" width="50" onerror="this.onerror=null;this.src='<?= base_url('assets/img/profile/default.jpg') ?>';"></td>
            <td>
              <a href="#" class="badge badge-success btn-edit-mahasiswa" data-id="<?= $m['id'] ?>">edit</a>
              <a href="#" class="badge badge-danger btn-delete-mahasiswa" data-id="<?= $m['id'] ?>">delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="7" class="text-center">Belum ada data mahasiswa.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  </div>





<!-- Modal: Tambah Mahasiswa -->
<div class="modal fade" id="newMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="newMahasiswaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newMahasiswaLabel">Tambah Mahasiswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('user/mahasiswa'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_siswa">Nama mahasiswa</label>
            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" placeholder="Masukkan nama mahasiswa" value="<?= set_value('nama_siswa') ?>" required>
            <?= form_error('nama_siswa', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="nim">Nim</label>
            <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM" value="<?= set_value('nim') ?>" required>
            <?= form_error('nim', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="prodi_id">Prodi</label>
            <select class="form-control" id="prodi_id" name="prodi_id" required>
              <option value="">-- Pilih Prodi --</option>
              <?php
                if (isset($prodi_list) && is_array($prodi_list)) {
                  foreach ($prodi_list as $p) {
                    echo '<option value="'.htmlspecialchars($p['id']).'" '.set_select('prodi_id', $p['id']).'>'.htmlspecialchars($p['nama_prodi']).'</option>';
                  }
                }
              ?>
            </select>
            <?= form_error('prodi_id', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="L" <?= set_select('jenis_kelamin', 'L'); ?>>Laki-laki</option>
              <option value="P" <?= set_select('jenis_kelamin', 'P'); ?>>Perempuan</option>
            </select>
            <?= form_error('jenis_kelamin', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            <?= form_error('image', '<small class="text-danger pl-1">', '</small>'); ?>
            <?= isset($upload_error) ? $upload_error : '' ?>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
 </div>

<!-- Modal: Edit Mahasiswa -->
<div class="modal fade" id="editMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="editMahasiswaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMahasiswaLabel">Edit Mahasiswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditMahasiswa" enctype="multipart/form-data">
        <input type="hidden" name="id" id="em_id">
        <div class="modal-body">
          <div class="form-group">
            <label for="em_nama_siswa">Nama mahasiswa</label>
            <input type="text" class="form-control" id="em_nama_siswa" name="nama_siswa" required>
            <small class="text-danger pl-1" id="err_em_nama_siswa"></small>
          </div>
          <div class="form-group">
            <label for="em_nim">NIM</label>
            <input type="text" class="form-control" id="em_nim" name="nim" required>
            <small class="text-danger pl-1" id="err_em_nim"></small>
          </div>
          <div class="form-group">
            <label for="em_prodi_id">Prodi</label>
            <select class="form-control" id="em_prodi_id" name="prodi_id" required>
              <option value="">-- Pilih Prodi --</option>
              <?php
                if (isset($prodi_list) && is_array($prodi_list)) {
                  foreach ($prodi_list as $p) {
                    echo '<option value="'.htmlspecialchars($p['id']).'">'.htmlspecialchars($p['nama_prodi']).'</option>';
                  }
                }
              ?>
            </select>
            <small class="text-danger pl-1" id="err_em_prodi_id"></small>
          </div>
          <div class="form-group">
            <label for="em_jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" id="em_jenis_kelamin" name="jenis_kelamin" required>
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
            <small class="text-danger pl-1" id="err_em_jenis_kelamin"></small>
          </div>
          <div class="form-group">
            <label for="em_image">Image (biarkan kosong jika tidak diganti)</label>
            <input type="file" class="form-control-file" id="em_image" name="image" accept="image/*">
            <small class="text-danger pl-1" id="err_em_image"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
@media print {

  /* SEMUA HALAMAN DISUMBAT */
  body * {
    visibility: hidden;
  }

  /* HANYA PRINT AREA */
  .print-only,
  .print-only * {
    visibility: visible;
  }

  .print-only {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    font-size: 12px;
  }

  /* MATIKAN DATATABLE TOTAL */
  .dataTables_wrapper,
  .dataTable,
  table.dataTable {
    display: none !important;
  }
}

@media screen {
  .print-only {
    display: none;
  }
}
</style>

<!-- AREA KHUSUS CETAK -->
<div id="print-area" class="print-only">

  <h3 style="text-align:center;margin:0;">Data Mahasiswa</h3>
  <div style="text-align:center;font-size:12px;">
    Dicetak: <?= htmlspecialchars($printed_at ?? '') ?> |
    Filter Jenis Kelamin: <?= htmlspecialchars($filter_jk_label ?? 'Semua') ?>
  </div>
  <hr>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Nama</th>
        <th>NIM</th>
        <th>Prodi</th>
        <th>Jenis Kelamin</th>
        <th>Image</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; foreach ($mahasiswa as $m): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($m['nama_siswa']) ?></td>
        <td><?= htmlspecialchars($m['nim']) ?></td>
        <td><?= htmlspecialchars($m['prodi_name']) ?></td>
        <td><?= htmlspecialchars($m['jenis_kelamin_label']) ?></td>
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
    </tbody>
  </table>

</div>



    <?php if ((isset($open_modal) && $open_modal)
    || form_error('nama_siswa')
    || form_error('nim')
    || form_error('prodi_id')
    || form_error('jenis_kelamin')
    || form_error('image')): ?>
    <script>
    (function(){
      function openModal(){
        if (window.jQuery && typeof $('#newMahasiswaModal').modal === 'function') {
          $('#newMahasiswaModal').modal('show');
        } else {
          // jQuery/Bootstrap belum siap, coba lagi setelah window load
          window.addEventListener('load', function(){
            if (window.jQuery && typeof $('#newMahasiswaModal').modal === 'function') {
              $('#newMahasiswaModal').modal('show');
            }
          });
        }
      }
      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        openModal();
      } else {
        document.addEventListener('DOMContentLoaded', openModal);
      }
    })();
    </script>
    <?php endif; ?>

<script>





(function(){
  function withJQ(fn){
    if (window.jQuery) { fn(window.jQuery); return; }
    var t = setInterval(function(){ if (window.jQuery) { clearInterval(t); fn(window.jQuery); } }, 100);
  }

  function init($){
    $(document).on('change', '#filter_jk', function(){
      var f = $(this).closest('form');
      if (f.length) f.submit();
    });

    var csrfName = '<?= isset($csrf_name) ? $csrf_name : '' ?>';
    var csrfHash = '<?= isset($csrf_hash) ? $csrf_hash : '' ?>';
    function addCsrf(obj){ if (csrfName) { obj[csrfName] = csrfHash; } return obj; }

    // Edit: fetch row and open modal
    $(document).on('click', '.btn-edit-mahasiswa', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      $.post('<?= site_url('user/getmahasiswarow') ?>', addCsrf({id: id}), function(resp){
        try { var row = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){ row = {}; }
        if (row && row.id){
          $('#em_id').val(row.id);
          $('#em_nama_siswa').val(row.nama_siswa || '');
          $('#em_nim').val(row.nim || '');
          $('#em_prodi_id').val(row.prodi_id || '');
          $('#em_jenis_kelamin').val(row.jenis_kelamin || '');
          $('#editMahasiswaModal').modal('show');
        }
      });
    });

    // Update submit via AJAX with FormData
    $('#formEditMahasiswa').on('submit', function(e){
      e.preventDefault();
      var form = document.getElementById('formEditMahasiswa');
      var fd = new FormData(form);
      if (csrfName) { fd.append(csrfName, csrfHash); }
      $.ajax({
        url: '<?= site_url('user/updatemahasiswa') ?>',
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(resp){
          try { var r = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){ r = {status:false}; }
          if (r.status){
            location.reload();
          } else if (r.errors){
            $('#err_em_nama_siswa').html(r.errors.nama_siswa || '');
            $('#err_em_nim').html(r.errors.nim || '');
            $('#err_em_prodi_id').html(r.errors.prodi_id || '');
            $('#err_em_jenis_kelamin').html(r.errors.jenis_kelamin || '');
            $('#err_em_image').html(r.errors.image || '');
          }
        }
      });
    });

    // Delete
    $(document).on('click', '.btn-delete-mahasiswa', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      if (!confirm('Yakin hapus data ini?')) return;
      $.post('<?= site_url('user/deletemahasiswa') ?>', addCsrf({id: id}), function(resp){
        try { var r = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){ r = {status:false}; }
        if (r.status){ location.reload(); }
      });
    });

    (function(){
      function _initMahasiswaDT($){
        var t = document.getElementById('mahasiswatable');
        if (!t) return true;
        if ($ && $.fn && $.fn.DataTable){
          window._mhsDT = $('#mahasiswatable').DataTable({ order: [], columnDefs: [{ targets: [0,5,6], orderable: false }], pageLength: 10 });
          return true;
        }
        if (typeof DataTable !== 'undefined'){
          window._mhsDT = new DataTable('#mahasiswatable', { order: [], columnDefs: [{ targets: [0,5,6], orderable: false }], pageLength: 10 });
          return true;
        }
        return false;
      }
      if (!_initMahasiswaDT($)){
        var _ti = setInterval(function(){ if (_initMahasiswaDT($)) clearInterval(_ti); }, 150);
      }
    })();
  }

  if (document.readyState === 'complete' || document.readyState === 'interactive') withJQ(init);
  else document.addEventListener('DOMContentLoaded', function(){ withJQ(init); });
})();
</script>


