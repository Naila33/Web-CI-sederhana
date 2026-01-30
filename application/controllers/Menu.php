<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property Menu_model $menu
 */

class Menu extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        is_logged_in();
    }


    public function index()
    {
        $data['title'] = 'Menu management';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['open_modal'] = false;

        $this->form_validation->set_rules('menu', 'Menu', 'required|trim');

        if ($this->form_validation->run() == false) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data['open_modal'] = true;
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added!</div>');
            redirect('menu');
        }
    }

    public function subMenu()
    {
        $data['title'] = 'Sub menu management';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('userr')->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['open_modal'] = false;

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if($this->form_validation->run() == false){

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['open_modal'] = true;
        }
        
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_sub_menu', [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New sub menu added!</div>');
            redirect('menu/submenu');
        }
    }


    public function getsubmenu()
    {
        $this->load->model('Menu_model');

        $list = $this->Menu_model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $row) {
            $no++;
            $data[] = [
                'no'      => $no,
                'title'   => $row->title,
                'menu' => $row->menu,
                'url' => $row->url,
                'icon'    => $row->icon,
                'is_active'  => $row->is_active,
                'aksi'    => '<a href="" class="badge badge-success">edit</a> <a href="" class="badge badge-danger">delete</a>'
            ];
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->Menu_model->count_all(),
            "recordsFiltered" => $this->Menu_model->count_filtered(),
            "data"            => $data
        ]);
    }

    public function getmenumanagement()
    {
        $this->load->model('Menu_model');

        $list = $this->Menu_model->get_menu_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $row) {
            $no++;
            $data[] = [
                'no'      => $no,
                'menu'    => $row->menu,
                'aksi'    => '<a href="" class="badge badge-success">edit</a> <a href="" class="badge badge-danger">delete</a>'
            ];
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->Menu_model->count_all_menu(),
            "recordsFiltered" => $this->Menu_model->count_filtered_menu(),
            "data"            => $data
        ]);
    }
    
}
