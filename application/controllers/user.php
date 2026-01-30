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
    foreach ($data['mahasiswa'] as $row) {
        $row['prodi_name'] = isset($prodi_map[$row['prodi_id']]) ? $prodi_map[$row['prodi_id']] : $row['prodi_id'];
        $row['jenis_kelamin_label'] = ($row['jenis_kelamin'] === 'L') ? 'Laki-laki' : (($row['jenis_kelamin'] === 'P') ? 'Perempuan' : $row['jenis_kelamin']);
        if (empty($row['image'])) { $row['image'] = 'default.jpg'; }
        $decorated[] = $row;
    }
    $data['mahasiswa'] = $decorated;

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
}



