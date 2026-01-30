<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    var $table = 'user_sub_menu';
    var $column_order = [null, 'menu_id', 'title', 'url', 'icon', 'is_active', null];
    var $column_search = ['title', 'menu_id', 'url'];
    var $order = ['title' => 'asc'];

    public function getsubmenu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                  FROM `user_sub_menu` JOIN `user_menu`
                  ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
        ";
        return $this->db->query($query)->result_array();
    }

    private function _get_datatables_query()
    {
        $this->db->select('user_sub_menu.*, user_menu.menu');
        $this->db->from($this->table);
        $this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');

        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order'][0]['column']],
                $_POST['order'][0]['dir']
            );
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }


    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    // Function untuk menu management
    var $table_menu = 'user_menu';
    var $column_order_menu = [null, 'menu', null];
    var $column_search_menu = ['menu'];
    var $order_menu = ['menu' => 'asc'];

    private function _get_menu_datatables_query()
    {
        $this->db->select('*');
        $this->db->from($this->table_menu);

        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            foreach ($this->column_search_menu as $item) {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order_menu[$_POST['order'][0]['column']],
                $_POST['order'][0]['dir']
            );
        } else {
            $this->db->order_by(key($this->order_menu), $this->order_menu[key($this->order_menu)]);
        }
    }

    public function get_menu_datatables()
    {
        $this->_get_menu_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered_menu()
    {
        $this->_get_menu_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all_menu()
    {
        return $this->db->count_all($this->table_menu);
    }
}
