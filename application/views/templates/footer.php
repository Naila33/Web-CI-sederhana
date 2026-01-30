<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Web programing UNPAS <?= date('Y'); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('Auth/logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css">
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>

<script>
    //ini script datatable yang tadi
</script>

<script>
    $('#table').DataTable({
        processing: true,
        serverSide: true,
        order: [],

        ajax: {
            url: "<?= site_url('menu/getsubmenu') ?>",
            type: "POST"
        },

        columnDefs: [{
            targets: [0, 6], // No & Aksi
            orderable: false
        }],

        columns: [{
                data: 'no'
            },
            {
                data: 'title'
            },
            {
                data: 'menu'
            },
            {
                data: 'url'
            },
            {
                data: 'icon'
            },
            {
                data: 'is_active'
            },
            {
                data: 'aksi'
            }
        ]
    });

    $('#menuTable').DataTable({
        processing: true,
        serverSide: true,
        order: [],

        ajax: {
            url: "<?= site_url('menu/getmenumanagement') ?>",
            type: "POST"
        },

        columnDefs: [{
            targets: [0, 2], // No & Aksi
            orderable: false
        }],

        columns: [{
                data: 'no'
            },
            {
                data: 'menu'
            },
            {
                data: 'aksi'
            }
        ]
    });


    $('#prodiTable').DataTable({
    processing: true,
    serverSide: true,
    order: [],
    ajax: {
        url: "<?= site_url('user/getprodi') ?>",
        type: "POST"
    },
    columnDefs: [{
        targets: [0, 5], // No & Aksi
        orderable: false
    }],
    columns: [
        { data: 'no' },
        { data: 'kode_prodi' },
        { data: 'nama_prodi' },
        { data: 'jenjang' },
        { data: 'fakultas' },
        { data: 'aksi' }
    ]
});


// Prodi: Edit - buka modal dan isi data
$(document).on('click', '.btn-edit-prodi', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    $.post('<?= site_url('user/getprodirow') ?>', { id: id }, function(res) {
        if (!res || !res.id) return;
        $('#e_id').val(res.id);
        $('#e_kode_prodi').val(res.kode_prodi);
        $('#e_nama_prodi').val(res.nama_prodi);
        $('#e_jenjang').val(res.jenjang);
        $('#e_fakultas').val(res.fakultas);
        // reset pesan error
        $('#err_kode_prodi, #err_nama_prodi, #err_jenjang, #err_fakultas').text('');
        $('#editProdiModal').modal('show');
    }, 'json');
});

// Prodi: Edit - submit via AJAX
$('#formEditProdi').on('submit', function(e) {
    e.preventDefault();
    $('#err_kode_prodi, #err_nama_prodi, #err_jenjang, #err_fakultas').text('');
    $.post('<?= site_url('user/updateprodi') ?>', $(this).serialize(), function(res) {
        if (res && res.status) {
            $('#editProdiModal').modal('hide');
            $('#prodiTable').DataTable().ajax.reload(null, false);
        } else if (res && res.errors) {
            $('#err_kode_prodi').text(res.errors.kode_prodi || '');
            $('#err_nama_prodi').text(res.errors.nama_prodi || '');
            $('#err_jenjang').text(res.errors.jenjang || '');
            $('#err_fakultas').text(res.errors.fakultas || '');
        }
    }, 'json');
});

// Prodi: Delete
$(document).on('click', '.btn-delete-prodi', function(e) {
    e.preventDefault();
    if (!confirm('Yakin hapus prodi ini?')) return;
    const id = $(this).data('id');
    $.post('<?= site_url('user/deleteprodi') ?>', { id: id }, function(res) {
        if (res && res.status) {
            $('#prodiTable').DataTable().ajax.reload(null, false);
        } else {
            alert(res && res.message ? res.message : 'Gagal menghapus');
        }
    }, 'json');
});


    $('.form-check-input').on('click', function() {
        const menuId = $(this).data('menu');
        const roleId = $(this).data('role');

        $.ajax({
            url: "<?= base_url('admin/changeaccess'); ?>",
            type: 'post',
            data: {
                menuId: menuId,
                roleId: roleId
            },
            success: function() {
                document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
            }
        });
    });
</script>

</body>

</html>