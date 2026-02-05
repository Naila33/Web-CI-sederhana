<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 */

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        is_logged_in();
    }




    public function index()
    {
        $data['title'] = 'My profile';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }



    public function edit()
    {
        $data['title'] = 'Edit profile';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();

        $this->form_validation->set_rules('name', 'Full name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // cek jika ad gambar 
            $upload_image = $_FILES['image'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = '2048';
                $config['upload_path']   = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }


                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('userr');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated.</div>');
            redirect('user');
        }
    }


    public function changepassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[3]|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else{
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            if(!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password</div>');
                redirect('user/changepassword');
            } else{
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password</div>');
                    redirect('user/changepassword');
                } else{
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                  $this->db->update('userr');

                  $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password changed</div>');
                  redirect('user/changepassword');
                }
            }
        }
        
    }

    public function prodi()
{
    $data['title'] = 'Progam study';
    $data['user'] = $this->db->where(
        'email',
        $this->session->userdata('email')
    )->get('userr')->row_array();

    $data['prodi'] = $this->db->get('prodi')->result_array();

    $data['open_modal'] = false;
    if (isset($this->security)) {
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
    }
    // pass CSRF tokens to view
    if (isset($this->security)) {
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
    }

    $this->form_validation->set_rules('kode_prodi', 'Kode Prodi', 'required|trim');
    $this->form_validation->set_rules('nama_prodi', 'Nama Prodi', 'required|trim');
    $this->form_validation->set_rules('jenjang', 'Jenjang', 'required|in_list[D1,D2,D3,D4,S1,S2,S3]');
    $this->form_validation->set_rules('fakultas', 'Fakultas', 'required|trim');

    if ($this->form_validation->run() == false) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['open_modal'] = true;
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/prodi', $data);
        $this->load->view('templates/footer');
    } else {
        $this->db->insert('prodi', [
            'kode_prodi' => $this->input->post('kode_prodi'),
            'nama_prodi' => $this->input->post('nama_prodi'),
            'jenjang'    => $this->input->post('jenjang'),
            'fakultas'   => $this->input->post('fakultas')
        ]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Prodi berhasil ditambahkan!</div>');
        redirect('user/prodi');
    }
}

    public function getprodirow()
    {
        $id = $this->input->post('id');
        $row = $this->db->get_where('prodi', ['id' => $id])->row_array();
        echo json_encode($row ?: []);
    }

    public function updateprodi()
    {
        $this->form_validation->set_rules('id', 'ID', 'required|integer');
        $this->form_validation->set_rules('kode_prodi', 'Kode Prodi', 'required|trim');
        $this->form_validation->set_rules('nama_prodi', 'Nama Prodi', 'required|trim');
        $this->form_validation->set_rules('jenjang', 'Jenjang', 'required|in_list[D1,D2,D3,D4,S1,S2,S3]');
        $this->form_validation->set_rules('fakultas', 'Fakultas', 'required|trim');

        if ($this->form_validation->run() == false) {
            echo json_encode([
                'status' => false,
                'errors' => [
                    'kode_prodi' => form_error('kode_prodi'),
                    'nama_prodi' => form_error('nama_prodi'),
                    'jenjang'    => form_error('jenjang'),
                    'fakultas'   => form_error('fakultas'),
                ]
            ]);
            return;
        }

        $id = (int)$this->input->post('id');
        $data = [
            'kode_prodi' => $this->input->post('kode_prodi'),
            'nama_prodi' => $this->input->post('nama_prodi'),
            'jenjang'    => $this->input->post('jenjang'),
            'fakultas'   => $this->input->post('fakultas'),
        ];
        $this->db->where('id', $id)->update('prodi', $data);
        echo json_encode(['status' => true, 'message' => 'Prodi berhasil diupdate']);
    }

    public function deleteprodi()
    {
        $id = (int)$this->input->post('id');
        if ($id) {
            $this->db->delete('prodi', ['id' => $id]);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
        }
    }

    public function getprodi()
    {
        $this->load->model('Prodi_model');

        $list = $this->Prodi_model->get_datatables();
        $data = [];
        $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

        foreach ($list as $row) {
            $no++;
            $data[] = [
                'no'          => $no,
                'kode_prodi'  => $row->kode_prodi,
                'nama_prodi'  => $row->nama_prodi,
                'jenjang'     => $row->jenjang,
                'fakultas'    => $row->fakultas,
                'aksi'        => '<a href="#" class="badge badge-success btn-edit-prodi" data-id="'.$row->id.'">edit</a> '
                                   .'<a href="#" class="badge badge-danger btn-delete-prodi" data-id="'.$row->id.'">delete</a>'
            ];
        }

        echo json_encode([
            "draw"            => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
            "recordsTotal"    => $this->Prodi_model->count_all(),
            "recordsFiltered" => $this->Prodi_model->count_filtered(),
            "data"            => $data
        ]);
    }


    public function mahasiswa()
{
    $data['title'] = 'Mahasiswa';
    $data['user'] = $this->db->where(
        'email',
        $this->session->userdata('email')
    )->get('userr')->row_array();

    $jk = $this->input->get('jenis_kelamin', true);
    if (in_array($jk, ['L', 'P'], true)) {
        $this->db->where('jenis_kelamin', $jk);
        $data['filter_jk'] = $jk;
    } else {
        $data['filter_jk'] = '';
    }
    $data['filter_jk_label'] = ($data['filter_jk'] === 'L') ? 'Laki-laki' : (($data['filter_jk'] === 'P') ? 'Perempuan' : 'Semua');
    $data['printed_at'] = date('d-m-Y H:i');
    $data['mahasiswa'] = $this->db->get('mahasiswa')->result_array();
    $data['prodi_list'] = $this->db->get('prodi')->result_array();
    $prodi_map = [];
    foreach ($data['prodi_list'] as $pl) { $prodi_map[$pl['id']] = $pl['nama_prodi']; }
    $data['prodi_map'] = $prodi_map;
    // decorate rows for presentation
    $decorated = [];
    $gender_counts = ['L' => 0, 'P' => 0];
    foreach ($data['mahasiswa'] as $row) {
        $row['prodi_name'] = isset($prodi_map[$row['prodi_id']]) ? $prodi_map[$row['prodi_id']] : $row['prodi_id'];
        $row['jenis_kelamin_label'] = ($row['jenis_kelamin'] === 'L') ? 'Laki-laki' : (($row['jenis_kelamin'] === 'P') ? 'Perempuan' : $row['jenis_kelamin']);
        if (empty($row['image'])) { $row['image'] = 'default.jpg'; }
        if ($row['jenis_kelamin'] === 'L') { $gender_counts['L']++; }
        elseif ($row['jenis_kelamin'] === 'P') { $gender_counts['P']++; }
        $decorated[] = $row;
    }
    $data['mahasiswa'] = $decorated;
    $data['gender_counts'] = $gender_counts;

    $data['open_modal'] = false;

    $this->form_validation->set_rules('nama_siswa', 'Nama siswa', 'required|trim');
    $this->form_validation->set_rules('nim', 'Nim', 'required|trim');
    $this->form_validation->set_rules('prodi_id', 'Prodi', 'required|trim');
    $this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]');
    $this->form_validation->set_rules('image', 'image', 'trim');

    if ($this->form_validation->run() == false) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['open_modal'] = true;
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/mahasiswa', $data);
        $this->load->view('templates/footer');
    } else {
        $new_image = '';
        if (!empty($_FILES['image']['name'])) {
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = '2048';
            $config['upload_path']   = './assets/img/profile/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $new_image = $this->upload->data('file_name');
            } else {
                $data['upload_error'] = '<small class=\"text-danger pl-1\">'.$this->upload->display_errors('', '').'</small>';
                $data['open_modal'] = true;
                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('user/mahasiswa', $data);
                $this->load->view('templates/footer');
                return;
            }
        } else {
            $data['upload_error'] = '<small class=\"text-danger pl-1\">Image wajib diunggah.</small>';
            $data['open_modal'] = true;
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/mahasiswa', $data);
            $this->load->view('templates/footer');
            return;
        }

        $this->db->insert('mahasiswa', [
            'nama_siswa' => $this->input->post('nama_siswa'),
            'nim' => $this->input->post('nim'),
            'prodi_id'    => $this->input->post('prodi_id'),
            'jenis_kelamin'   => $this->input->post('jenis_kelamin'),
            'image'   => $new_image
        ]);
        $this->session->set_flashdata('message', '<div class=\"alert alert-success\" role=\"alert\">Mahasiswa berhasil ditambahkan!</div>');
        redirect('user/mahasiswa');
    }
}

    public function getmahasiswarow()
    {
        $id = $this->input->post('id');
        $row = $this->db->get_where('mahasiswa', ['id' => $id])->row_array();
        echo json_encode($row ?: []);
    }

    public function updatemahasiswa()
    {
        $this->form_validation->set_rules('id', 'ID', 'required|integer');
        $this->form_validation->set_rules('nama_siswa', 'Nama siswa', 'required|trim');
        $this->form_validation->set_rules('nim', 'Nim', 'required|trim');
        $this->form_validation->set_rules('prodi_id', 'Prodi', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]');
        $this->form_validation->set_rules('image', 'image', 'trim');

        if ($this->form_validation->run() == false) {
            echo json_encode([
                'status' => false,
                'errors' => [
                    'nama_siswa' => form_error('nama_siswa'),
                    'nim' => form_error('nim'),
                    'prodi_id' => form_error('prodi_id'),
                    'jenis_kelamin' => form_error('jenis_kelamin'),
                    'image' => form_error('image'),
                ]
            ]);
            return;
        }

        $id = (int)$this->input->post('id');
        $row = $this->db->get_where('mahasiswa', ['id' => $id])->row_array();

        $new_image = $row ? $row['image'] : '';
        if (!empty($_FILES['image']['name'])) {
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = '2048';
            $config['upload_path']   = './assets/img/profile/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $new_image = $this->upload->data('file_name');
            } else {
                echo json_encode([
                    'status' => false,
                    'errors' => [
                        'image' => '<small class="text-danger pl-1">'.$this->upload->display_errors('', '').'</small>'
                    ]
                ]);
                return;
            }
        }

        $data = [
            'nama_siswa' => $this->input->post('nama_siswa'),
            'nim' => $this->input->post('nim'),
            'prodi_id' => $this->input->post('prodi_id'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'image' => $new_image,
        ];
        $this->db->where('id', $id)->update('mahasiswa', $data);
        echo json_encode(['status' => true, 'message' => 'Mahasiswa berhasil diupdate']);
    }

    public function deletemahasiswa()
    {
        $id = (int)$this->input->post('id');
        if ($id) {
            $this->db->delete('mahasiswa', ['id' => $id]);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
        }
    }

    public function getmahasiswa()
    {
        $this->load->model('mahasiswa_model');

        $list = $this->mahasiswa_model->get_datatables();
        $data = [];
        $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

        foreach ($list as $row) {
            $no++;
            $data[] = [
                'no'          => $no,
                'nama_siswa'  => $row->nama_siswa,
                'nim'  => $row->nim,
                'prodi_id'     => $row->prodi_id,
                'jenis_kelamin'    => $row->jenis_kelamin,
                'image'    => $row->image,
                'aksi'        => '<a href="#" class="badge badge-success btn-edit-mahasiswa" data-id="'.$row->id.'">edit</a> '
                                   .'<a href="#" class="badge badge-danger btn-delete-mahasiswa" data-id="'.$row->id.'">delete</a>'
            ];
        }

        echo json_encode([
            "draw"            => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
            "recordsTotal"    => $this->Prodi_model->count_all(),
            "recordsFiltered" => $this->Prodi_model->count_filtered(),
            "data"            => $data
        ]);
    }

    public function mahasiswa_print()
    {
        $data['title'] = 'Cetak Data Mahasiswa';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();

        $jk = $this->input->get('jenis_kelamin', true);
        if (in_array($jk, ['L', 'P'], true)) {
            $this->db->where('jenis_kelamin', $jk);
            $data['filter_jk'] = $jk;
        } else {
            $data['filter_jk'] = '';
        }
        $data['filter_jk_label'] = ($data['filter_jk'] === 'L') ? 'Laki-laki' : (($data['filter_jk'] === 'P') ? 'Perempuan' : 'Semua');
        $data['printed_at'] = date('d-m-Y H:i');

        $data['mahasiswa'] = $this->db->get('mahasiswa')->result_array();
        $data['prodi_list'] = $this->db->get('prodi')->result_array();
        $prodi_map = [];
        foreach ($data['prodi_list'] as $pl) { $prodi_map[$pl['id']] = $pl['nama_prodi']; }
        $data['prodi_map'] = $prodi_map;
        // decorate rows for presentation
        $decorated = [];
        foreach ($data['mahasiswa'] as $row) {
            $row['prodi_name'] = isset($prodi_map[$row['prodi_id']]) ? $prodi_map[$row['prodi_id']] : $row['prodi_id'];
            $row['jenis_kelamin_label'] = ($row['jenis_kelamin'] === 'L') ? 'Laki-laki' : (($row['jenis_kelamin'] === 'P') ? 'Perempuan' : $row['jenis_kelamin']);
            if (empty($row['image'])) { $row['image'] = 'default.jpg'; }
            $decorated[] = $row;
        }
        $data['mahasiswa'] = $decorated;

        $this->load->view('user/mahasiswa_print', $data);
    }

    public function excel()
    {
        $jk = $this->input->get('jenis_kelamin', true);
        if (in_array($jk, ['L', 'P'], true)) {
            $this->db->where('jenis_kelamin', $jk);
        }

        $rows = $this->db->get('mahasiswa')->result_array();

        // build prodi map
        $prodi_list = $this->db->get('prodi')->result_array();
        $prodi_map = [];
        foreach ($prodi_list as $pl) { $prodi_map[$pl['id']] = $pl['nama_prodi']; }

        $filename = 'mahasiswa_' . date('Ymd_His') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<meta charset="UTF-8">';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>Nama Mahasiswa</th><th>NIM</th><th>Prodi</th><th>Jenis Kelamin</th><th>Image</th>';
        echo '</tr></thead><tbody>';
        $no = 1;
        foreach ($rows as $r) {
            $prodi_name = isset($prodi_map[$r['prodi_id']]) ? $prodi_map[$r['prodi_id']] : $r['prodi_id'];
            $jk_label = ($r['jenis_kelamin'] === 'L') ? 'Laki-laki' : (($r['jenis_kelamin'] === 'P') ? 'Perempuan' : $r['jenis_kelamin']);
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($r['nama_siswa'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($r['nim'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($prodi_name, ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($jk_label, ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($r['image'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        exit;
    }

    public function import_mahasiswa()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/mahasiswa');
            return;
        }

        $redirect_url = 'user/mahasiswa';
        $jk = $this->input->get('jenis_kelamin', true);
        if (in_array($jk, ['L','P'], true)) {
            $redirect_url .= '?jenis_kelamin=' . urlencode($jk);
        }

        if (!isset($_FILES['file_import']) || empty($_FILES['file_import']['tmp_name'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">File CSV belum dipilih.</div>');
            redirect($redirect_url);
            return;
        }

        $tmp = $_FILES['file_import']['tmp_name'];
        $handle = @fopen($tmp, 'r');
        if (!$handle) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal membuka file untuk dibaca.</div>');
            redirect($redirect_url);
            return;
        }

        // ambil daftar prodi untuk map nama->id
        $prodi_rows = $this->db->get('prodi')->result_array();
        $prodi_map_by_name = [];
        $prodi_ids = [];
        foreach ($prodi_rows as $p) {
            $prodi_map_by_name[strtolower(trim($p['nama_prodi']))] = (int)$p['id'];
            $prodi_ids[(int)$p['id']] = true;
        }

        $success = 0;
        $failed = 0;
        $lineNo = 0;

        // baca header
        $header = false;
        $colIndex = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            // jika delimiter koma gagal (satu kolom besar), coba ;
            if (count($row) === 1) {
                $row = str_getcsv($row[0], ';');
            }
            // skip baris kosong
            $allEmpty = true; foreach ($row as $c) { if (trim($c) !== '') { $allEmpty = false; break; } }
            if ($allEmpty) { continue; }

            $lineNo++;
            if ($header === false) {
                $header = array_map(function($h){ return strtolower(trim($h)); }, $row);
                // petakan kolom yang dikenali
                $want = ['nama_siswa','nim','prodi_id','prodi','jenis_kelamin'];
                foreach ($want as $w) {
                    $idx = array_search($w, $header, true);
                    if ($idx !== false) { $colIndex[$w] = $idx; }
                }
                // minimal wajib: nama_siswa, nim, (prodi_id atau prodi), jenis_kelamin
                if (!isset($colIndex['nama_siswa']) || !isset($colIndex['nim']) || (!isset($colIndex['prodi_id']) && !isset($colIndex['prodi'])) || !isset($colIndex['jenis_kelamin'])) {
                    fclose($handle);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Header CSV tidak valid. Wajib ada kolom: nama_siswa, nim, (prodi_id atau prodi), jenis_kelamin.</div>');
                    redirect($redirect_url);
                    return;
                }
                continue;
            }

            // ambil nilai per kolom
            $nama = isset($colIndex['nama_siswa']) ? trim($row[$colIndex['nama_siswa']] ?? '') : '';
            $nim  = isset($colIndex['nim']) ? trim($row[$colIndex['nim']] ?? '') : '';
            $jenis = isset($colIndex['jenis_kelamin']) ? trim($row[$colIndex['jenis_kelamin']] ?? '') : '';
            $image = isset($colIndex['image']) ? trim($row[$colIndex['image']] ?? '') : '';

            // normalisasi jenis kelamin
            $j = strtoupper(substr($jenis,0,1));
            if ($j === 'L') { $jenis = 'L'; }
            elseif ($j === 'P') { $jenis = 'P'; }
            else { $jenis = ''; }

            // prodi id dari id atau nama
            $prodi_id = null;
            if (isset($colIndex['prodi_id'])) {
                $pidRaw = trim($row[$colIndex['prodi_id']] ?? '');
                if ($pidRaw !== '' && ctype_digit($pidRaw)) {
                    $pid = (int)$pidRaw;
                    if (isset($prodi_ids[$pid])) { $prodi_id = $pid; }
                }
            }
            if ($prodi_id === null && isset($colIndex['prodi'])) {
                $pname = strtolower(trim($row[$colIndex['prodi']] ?? ''));
                if ($pname !== '' && isset($prodi_map_by_name[$pname])) {
                    $prodi_id = $prodi_map_by_name[$pname];
                }
            }

            if ($nama === '' || $nim === '' || !$prodi_id || ($jenis !== 'L' && $jenis !== 'P')) {
                $failed++;
                continue;
            }

            $data = [
                'nama_siswa'    => $nama,
                'nim'           => $nim,
                'prodi_id'      => $prodi_id,
                'jenis_kelamin' => $jenis,
                'image'         => $image,
            ];

            $this->db->insert('mahasiswa', $data);
            if ($this->db->affected_rows() > 0) { $success++; } else { $failed++; }
        }
        fclose($handle);

        $msg = '<div class="alert alert-info" role="alert">Import selesai. Berhasil: ' . (int)$success . ', Gagal: ' . (int)$failed . '.</div>';
        $this->session->set_flashdata('message', $msg);
        redirect($redirect_url);
    }

    public function mahasiswa_chart()
    {
        $data['title'] = 'Chart Mahasiswa';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();

        // optional: respect current filter via query param
        $jk = $this->input->get('jenis_kelamin', true);
        if (in_array($jk, ['L', 'P'], true)) {
            $this->db->where('jenis_kelamin', $jk);
            $data['filter_jk'] = $jk;
        } else {
            $data['filter_jk'] = '';
        }

        $rows = $this->db->get('mahasiswa')->result_array();
        $gender_counts = ['L' => 0, 'P' => 0];
        foreach ($rows as $r) {
            if ($r['jenis_kelamin'] === 'L') { $gender_counts['L']++; }
            elseif ($r['jenis_kelamin'] === 'P') { $gender_counts['P']++; }
        }
        $data['gender_counts'] = $gender_counts;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/mahasiswa_chart', $data);
        $this->load->view('templates/footer');
    }
}



