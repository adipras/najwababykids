<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_accounts extends MX_Controller
{

	function __construct() {
		parent::__construct();
	}

	function manage()
    {
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $data['flash'] = $this->session->flashdata('account');

        $data['query'] = $this->get('lastname');
        $data['view_file'] = "home";
        echo Modules::run('templates/admin', $data);
    }

    function update_pword() {
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $update_id = $this->uri->segment(3);
        $submit = $this->input->post('submit', TRUE);

        if(!is_numeric($update_id)) {
            redirect('store_accounts/manage');
        } elseif ($submit=="Cancel") {
            redirect('store_accounts/create/'.$update_id);
        }

        if($submit=="Submit") {
            //process the form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('pword', 'Password', 'required|min_length[7]|max_length[35]');
            $this->form_validation->set_rules('repeat_pword', 'Repeat Password', 'required|matches[pword]');

            if($this->form_validation->run() == TRUE) {
                //get the variables
                $pword = $this->input->post('pword', TRUE);
                $this->load->module('site_security');
                $data['pword'] = $this->site_security->_hash_string($pword);

                //update the account details
                $this->_update($update_id, $data);
                $flash_msg = "The account Password was successfully update.";
                $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                $this->session->set_flashdata('item', $value);
                redirect('store_accounts/create/'.$update_id);
            }
        }

        $data['headline'] = "Update Password";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "update_pword";
        echo Modules::run('templates/admin', $data);

    }

    function create() {
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $update_id = $this->uri->segment(3);
        $submit = $this->input->post('submit', TRUE);



        if($submit=="Cancel") {
            redirect('store_accounts/manage');
        }

        if($submit=="Submit") {
            //process the form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('firstname', 'First Name', 'required');

            if($this->form_validation->run() == TRUE) {
                //get the variables
                $data = $this->fetch_data_from_post();

                if(is_numeric($update_id)) {
                    //update the account details
                    $this->_update($update_id, $data);
                    $flash_msg = "The account details were successfully update.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('store_accounts/create/'.$update_id);
                } else {
                    //insert a new account
                    $data['date_made'] = time();
                    $this->_insert($data);
                    $update_id = $this->get_max();//get the ID of the new account
                    $flash_msg = "The account was successfully added.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('store_accounts/create/'.$update_id);
                }
            }
        }

        if((is_numeric($update_id)) && ($submit!="Submit")) {
            $data = $this->fetch_data_from_db($update_id);
        }else {
            $data = $this->fetch_data_from_post();
        }

        if(!is_numeric($update_id)) {
            $data['headline'] = "Add New Account";
        } else {
            $data['headline'] = "Update Account Details";
        }

        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        //$data['module'] = "store_accounts";
        $data['view_file'] = "create";
        echo Modules::run('templates/admin', $data);

        //$this->load->module('templates');
        //$this->templates->admin($data);
    }

    function autogen() {
    	$mysql_query = "show columns from store_accounts";
    	$query = $this->_custom_query($mysql_query);
        /*
    	foreach ($query->result() as $row) {
    		$columns_name = $row->Field;

    		if($columns_name!="id") {
    			echo '$data[\''.$columns_name.'\'] = $this->input->post(\''.$columns_name.'\', TRUE);<br>';
    		}
    	}
    	echo "<hr>";
    	foreach ($query->result() as $row) {
    		$columns_name = $row->Field;

    		if($columns_name!="id") {
    			echo '$data[\''.$columns_name.'\'] = $row->'.$columns_name.';<br>';
    		}
    	}*/
        foreach ($query->result() as $row) {
            $columns_name = $row->Field;

            if($columns_name!="id") {
                 $var = '<div class="control-group">
                            <label class="control-label" for="typeahead">'.ucfirst($columns_name).' </label>
                            <div class="controls">
                                <input type="text" class="span6" name="'.$columns_name.'" value="<?= $'.$columns_name.' ?>">
                            </div>
                        </div>';
                echo htmlentities($var);
                echo "<br>";
            }
        }        
    }

    function fetch_data_from_post() {
        $data['username'] = $this->input->post('username', TRUE);
        $data['firstname'] = $this->input->post('firstname', TRUE);
		$data['lastname'] = $this->input->post('lastname', TRUE);
		$data['company'] = $this->input->post('company', TRUE);
		$data['address1'] = $this->input->post('address1', TRUE);
		$data['address2'] = $this->input->post('address2', TRUE);
		$data['town'] = $this->input->post('town', TRUE);
		$data['country'] = $this->input->post('country', TRUE);
		$data['postcode'] = $this->input->post('postcode', TRUE);
		$data['telnum'] = $this->input->post('telnum', TRUE);
		$data['email'] = $this->input->post('email', TRUE);
        return $data;
    }

    function fetch_data_from_db($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['username'] = $row->username;
            $data['firstname'] = $row->firstname;
			$data['lastname'] = $row->lastname;
			$data['company'] = $row->company;
			$data['address1'] = $row->address1;
			$data['address2'] = $row->address2;
			$data['town'] = $row->town;
			$data['country'] = $row->country;
			$data['postcode'] = $row->postcode;
			$data['telnum'] = $row->telnum;
			$data['email'] = $row->email;
			$data['date_made'] = $row->date_made;
			$data['pword'] = $row->pword;
        }
        if(!isset($data)) {
            $data = "";
        }

        return $data;
    }

	function get($order_by) {
		$this->load->model('mdl_store_accounts');
		$query = $this->mdl_store_accounts->get($order_by);
		return $query;
	}
	
	function get_with_limit($limit, $offset, $order_by) {
		$this->load->model('mdl_store_accounts');
		$query = $this->mdl_store_accounts->get_with_limit($limit, $offset, $order_by);
		return $query;
	}
	
	function get_where($id) {
		$this->load->model('mdl_store_accounts');
		$query = $this->mdl_store_accounts->get_where($id);
		return $query;
	}
    
    function get_where_custom($col, $value) {
        $this->load->model('mdl_store_accounts');
        $query = $this->mdl_store_accounts->get_where_custom($col, $value);
        return $query;
    }
	
	function get_with_double_condition($col1, $value1, $col2, $value2) {
		$this->load->model('mdl_store_accounts');
		$query = $this->mdl_store_accounts->get_with_double_condition($col1, $value1, $col2, $value2);
		return $query;
	}
	
	function _insert($data) {
		$this->load->model('mdl_store_accounts');
		$this->mdl_store_accounts->_insert($data);
	}
	
	function _update($id, $data) {
		$this->load->model('mdl_store_accounts');
		$this->mdl_store_accounts->_update($id, $data);
	}
	
	function _delete($id) {
		$this->load->model('mdl_store_accounts');
		$this->mdl_store_accounts->_delete($id);
	}
	
	function count_where($column, $value) {
		$this->load->model('mdl_store_accounts');
		$count = $this->mdl_store_accounts->count_where($column, $value);
		return $count;
	}
	
	function get_max() {
		$this->load->model('mdl_store_accounts');
		$max_id = $this->mdl_store_accounts->get_max();
		return $max_id;
	}
	
	function _custom_query($mysql_query) {
		$this->load->model('mdl_store_accounts');
		$query = $this->mdl_store_accounts->_custom_query($mysql_query);
		return $query;
	}
}
	
	