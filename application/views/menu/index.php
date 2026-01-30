
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

                    

<div class="row">
    <div class="col-lg-6"></div>

    <?= $this->session->flashdata('message'); ?>


<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newmenumodal">Add New Menu</a>

<table id="menuTable" class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Menu</th>
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
<div class="modal fade" id="newmenumodal" tabindex="-1" role="dialog" aria-labelledby="newmenumodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newmenumodalLabel">Add new menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu'); ?>" method="post">
      <div class="modal-body">
        <div class="form-group">
            <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name" value="<?= set_value('menu') ?>" required>
            <?= form_error('menu', '<small class="text-danger">', '</small>'); ?>
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
<?php if ((isset($open_modal) && $open_modal) || form_error('menu')): ?>
<script>
$(document).ready(function() {
    $('#newmenumodal').modal('show');
});
</script>
<?php endif; ?>
        

