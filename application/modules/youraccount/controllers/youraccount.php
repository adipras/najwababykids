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
                //figure out the user_id
                $col1 = 'username';
                $value1 = $this->input->post('username', TRUE);
                $col2 = 'email';
                $value2 = $this->input->post('username', TRUE);
                $query = $this->store_accounts->get_with_double_condition($col1, $value1, $col2, $value2);
                foreach ($query->result() as $row) {
                    $user_id = $row->id;
                }

                //send them to the private page
                $this->_in_you_go($user_id);
            } else {
                echo validation_errors();
            }
        }
    }

    function _in_you_go($user_id) {
        echo "sending user $user_id to the private area";
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
        $this->load->module('store_accounts');
        $this->load->module('site_security');

        $error_msg = "You did not enter a correct username and/or password.";

        $col1 = 'username';
        $value1 = $str;
        $col2 = 'email';
        $value2 = $str;
        $query = $this->store_accounts->get_with_double_condition($col1, $value1, $col2, $value2);
        $num_rows = $query->num_rows();
        
        if($num_rows<1) {
            $this->form_validation->set_message('username_check', $error_msg);
            return FALSE;
        }

        foreach ($query->result() as $row) {
            $pword_on_table = $row->pword;
        }

        $pword = $this->input->post('pword', TRUE);
        $result = $this->site_security->_verify_hash($pword, $pword_on_table);

        if ($result==TRUE) {
            return TRUE;
        } else {
            $this->form_validation->set_message('username_check', $error_msg);
            return FALSE;
        }
    }
}

