<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>


<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newProdiModal">Tambah Prodi</a>

<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="prodiTable" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kode Prodi</th>
        <th scope="col">Nama Prodi</th>
        <th scope="col">Jenjang</th>
        <th scope="col">Fakultas</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($prodi)) : ?>
        <?php $i = 1; foreach ($prodi as $p) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= htmlspecialchars($p['kode_prodi'] ?? ''); ?></td>
            <td><?= htmlspecialchars($p['nama_prodi'] ?? ''); ?></td>
            <td><?= htmlspecialchars($p['jenjang'] ?? ''); ?></td>
            <td><?= htmlspecialchars($p['fakultas'] ?? ''); ?></td>
            <td>
              <a href="#" class="badge badge-success btn-edit-prodi" data-id="<?= $p['id'] ?>">edit</a>
<a href="#" class="badge badge-danger btn-delete-prodi" data-id="<?= $p['id'] ?>">delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="6" class="text-center">Belum ada data prodi.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  </div>

<!-- Modal: Tambah Prodi -->
<div class="modal fade" id="newProdiModal" tabindex="-1" role="dialog" aria-labelledby="newProdiModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newProdiModalLabel">Tambah Prodi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('user/prodi'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="kode_prodi">Kode Prodi</label>
            <input type="text" class="form-control" id="kode_prodi" name="kode_prodi" placeholder="Masukkan kode prodi" value="<?= set_value('kode_prodi') ?>" required>
            <?= form_error('kode_prodi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="nama_prodi">Nama Prodi</label>
            <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" placeholder="Masukkan nama prodi" value="<?= set_value('nama_prodi') ?>" required>
            <?= form_error('nama_prodi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="jenjang">Jenjang</label>
            <select class="form-control" id="jenjang" name="jenjang" required>
              <option value="">-- Pilih Jenjang --</option>
              <?php $opt = ['D1','D2','D3','D4','S1','S2','S3']; foreach ($opt as $j): ?>
                <option value="<?= $j ?>" <?= set_select('jenjang', $j); ?>><?= $j ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('jenjang', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="fakultas">Fakultas</label>
            <input type="text" class="form-control" id="fakultas" name="fakultas" placeholder="Masukkan fakultas" value="<?= set_value('fakultas') ?>" required>
            <?= form_error('fakultas', '<small class="text-danger pl-1">', '</small>'); ?>
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

    <!-- Modal: Edit Prodi -->
    <div class="modal fade" id="editProdiModal" tabindex="-1" role="dialog" aria-labelledby="editProdiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editProdiModalLabel">Edit Prodi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="formEditProdi">
            <input type="hidden" name="id" id="e_id">
            <div class="modal-body">
            <div class="form-group">
                <label for="e_kode_prodi">Kode Prodi</label>
                <input type="text" class="form-control" id="e_kode_prodi" name="kode_prodi" required>
                <small class="text-danger pl-1" id="err_kode_prodi"></small>
            </div>
            <div class="form-group">
                <label for="e_nama_prodi">Nama Prodi</label>
                <input type="text" class="form-control" id="e_nama_prodi" name="nama_prodi" required>
                <small class="text-danger pl-1" id="err_nama_prodi"></small>
            </div>
            <div class="form-group">
                <label for="e_jenjang">Jenjang</label>
                <select class="form-control" id="e_jenjang" name="jenjang" required>
                <option value="">-- Pilih Jenjang --</option>
                <?php $opt = ['D1','D2','D3','D4','S1','S2','S3']; foreach ($opt as $j): ?>
                    <option value="<?= $j ?>"><?= $j ?></option>
                <?php endforeach; ?>
                </select>
                <small class="text-danger pl-1" id="err_jenjang"></small>
            </div>
            <div class="form-group">
                <label for="e_fakultas">Fakultas</label>
                <input type="text" class="form-control" id="e_fakultas" name="fakultas" required>
                <small class="text-danger pl-1" id="err_fakultas"></small>
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

    <?php if ((isset($open_modal) && $open_modal)
    || form_error('kode_prodi')
    || form_error('nama_prodi')
    || form_error('jenjang')
    || form_error('fakultas')): ?>
    <script>
    $(document).ready(function(){
    $('#newProdiModal').modal('show');
    });
    </script>
    <?php endif; ?>
