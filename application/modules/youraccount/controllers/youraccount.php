<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Youraccount extends MX_Controller
{

	function __construct() {
		parent::__construct();
           $this->load->library('form_validation');
           $this->form_validation->CI =& $this;
	}

	function login() {
		$data['username'] = $this->input->post('username', TRUE);
        echo Modules::run('templates/login', $data);
	}

	function submit_login() {
		$submit = $this->input->post('submit', TRUE);

        if($submit=="Submit") {
            //process the form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[60]|callback_username_check');
            $this->form_validation->set_rules('pword', 'Password', 'required|min_length[7]|max_length[35]');

            if($this->form_validation->run() == TRUE) {
                //get the variables
                $this->_process_create_account();
                echo "<h1>Account Created</h1>";
                echo "<p>Please Sign In";
                die();
            } else {
            	$this->start();
            }
        }
	}

	function submit() {
		$submit = $this->input->post('submit', TRUE);

        if($submit=="Submit") {
            //process the form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[60]|is_unique[store_accounts.username]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[120]');
            $this->form_validation->set_rules('pword', 'Password', 'required|min_length[7]|max_length[35]');
            $this->form_validation->set_rules('repeat_pword', 'Repeat Password', 'required|matches[pword]');

            if($this->form_validation->run() == TRUE) {
                //get the variables
                $this->_process_create_account();
                echo "<h1>Account Created</h1>";
                echo "<p>Please Sign In";
                die();

                
            } else {
            	$this->start();
            }
            die();
        }
	}

	function _process_create_account() {
		$this->load->module('store_accounts');
		$data = $this->fetch_data_from_post();
		unset($data['repeat_pword']);

		$pword = $data['pword'];
        $this->load->module('site_security');
        $data['pword'] = $this->site_security->_hash_string($pword);
        $this->store_accounts->_insert($data);
	}
	
	function start() {
		$data = $this->fetch_data_from_post();
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "start";
        echo Modules::run('templates/public_bootstrap', $data);
	}

	function fetch_data_from_post() {
		$data['username'] = $this->input->post('username', TRUE);
		$data['email'] = $this->input->post('email', TRUE);
		$data['pword'] = $this->input->post('pword', TRUE);
		$data['repeat_pword'] = $this->input->post('repeat_pword', TRUE);
		return $data;
	}

    function username_check($str) {
        $item_url = url_title($str);
        $mysql_query = "select * from store_items where item_title = '$str' and item_url = '$item_url'";

        $update_id = $this->uri->segment(3);
        if(is_numeric($update_id)) {
            //this is an update
            $mysql_query .= " and id != $update_id";
        }

        $query = $this->_custom_query($mysql_query);
        $num_rows = $query->num_rows();

        if($num_rows>0) {
            $this->form_validation->set_message('item_check', 'The item title that you submitted is not available');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

