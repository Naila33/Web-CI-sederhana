<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>



  <div class="row">
    <div class="col-lg"></div>

    <?= $this->session->flashdata('message'); ?>


    <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newsubmenumodal">Add New Submenu</a>

    <table id="table" class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">title</th>
          <th scope="col">Menu</th>
          <th scope="col">Url</th>
          <th scope="col">icon</th>
          <th scope="col">Active</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
  </table>

</div>
<!-- /.container-fluid -->

</div>

<!-- Modal -->
<div class="modal fade" id="newsubmenumodal" tabindex="-1" role="dialog" aria-labelledby="newsubmenumodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newsubmenumodalLabel">Add new sub menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu/submenu'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" id="title" name="title" placeholder="Submenu title" value="<?= set_value('title') ?>" required>
            <?= form_error('title', '<small class="text-danger pl-3">', '</small>'); ?>
          </div>
          <div class="form-group">
            <select name="menu_id" id="menu_id" class="form-control" required>
              <option value="">Select menu</option>
              <?php foreach ($menu as $m) : ?>
                <option value="<?= $m['id'] ?>" <?= set_select('menu_id', $m['id']); ?>><?= $m['menu'] ?></option>
              <?php endforeach ?>
            </select>
            <?= form_error('menu_id', '<small class="text-danger pl-3">', '</small>'); ?>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="url" name="url" placeholder="Submenu url" value="<?= set_value('url') ?>" required>
            <?= form_error('url', '<small class="text-danger pl-3">', '</small>'); ?>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon" value="<?= set_value('icon') ?>" required>
            <?= form_error('icon', '<small class="text-danger pl-3">', '</small>'); ?>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" <?= set_checkbox('is_active', '1', true); ?>>
            <label class="form-chcek-label" for="is_active">
              Active?
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if (isset($open_modal) && $open_modal): ?>
<script>
$(document).ready(function() {
    $('#newsubmenumodal').modal('show');
});
</script>
<?php endif; ?>
