<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Webpages extends MX_Controller
{

	function __construct() {
		parent::__construct();
	}

    function manage() {
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $data['flash'] = $this->session->flashdata('item');
        $data['query'] = $this->get('page_url');
        $data['view_file'] = "home";
        echo Modules::run('templates/admin', $data);
    }

    function create() {
        $this->load->library('session');
        $update_id = $this->uri->segment(3);
        $submit = $this->input->post('submit', TRUE);

        if($submit=="Cancel") {
            redirect('webpages/manage');
        }

        if($submit=="Submit") {
            //process the form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('page_title', 'Page Title', 'required|max_length[250]');
            $this->form_validation->set_rules('page_content', 'Page Content', 'required');

            if($this->form_validation->run() == TRUE) {
                //get the variables
                $data = $this->fetch_data_from_post();
                $data['page_url'] = url_title($data['page_title']);

                if(is_numeric($update_id)) {
                    //update the page details
                    if($update_id<3) {
                        unset($data['page_url']);
                    }

                    $this->_update($update_id, $data);
                    $flash_msg = "The page details were successfully update.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('webpages/create/'.$update_id);
                } else {
                    //insert a new page
                    $this->_insert($data);
                    $update_id = $this->get_max();//get the ID of the new page
                    $flash_msg = "The page was successfully created.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('webpages/create/'.$update_id);
                }
            }
        }

        if((is_numeric($update_id)) && ($submit!="Submit")) {
            $data = $this->fetch_data_from_db($update_id);
        }else {
            $data = $this->fetch_data_from_post();
        }

        if(!is_numeric($update_id)) {
            $data['headline'] = "Add New Page";
        } else {
            $data['headline'] = "Update Page Details";
        }

        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        //$data['module'] = "webpages";
        $data['view_file'] = "create";
        echo Modules::run('templates/admin', $data);

        //$this->load->module('templates');
        //$this->templates->admin($data);
    }
    
    function deleteconf($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }elseif ($update_id<3) { //prevent them from deleting home and contactus
            redirect('site_security/not_allowed');
        }
           
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $data['headline'] = "Delete Page";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "deleteconf";
        echo Modules::run('templates/admin', $data);
    }

    function delete($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }
            
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $submit = $this->input->post('submit', TRUE);
        if($submit=="Cancel") {
            redirect('webpages/create/'.$update_id);
        } elseif ($submit=="Yes - Delete Page") {
            $this->_delete($update_id);

            $flash_msg = "The item page was successfully deleted.";
            $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
            $this->session->set_flashdata('item', $value);

            redirect('webpages/manage');
        }
    }
   
    function fetch_data_from_post() {
        $data['page_title'] = $this->input->post('page_title', TRUE);
        $data['page_keywords'] = $this->input->post('page_keywords', TRUE);
        $data['page_description'] = $this->input->post('page_description', TRUE);
        $data['page_content'] = $this->input->post('page_content', TRUE);
        return $data;
    }

    function fetch_data_from_db($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['page_title'] = $row->page_title;
            $data['page_url'] = $row->page_url;
            $data['page_keywords'] = $row->page_keywords;
            $data['page_content'] = $row->page_content;
            $data['page_description'] = $row->page_description;
        }
        if(!isset($data)) {
            $data = "";
        }

        return $data;
    }
	
	function get($order_by) {
		$this->load->model('mdl_webpages');
		$query = $this->mdl_webpages->get($order_by);
		return $query;
	}
	
	function get_with_limit($limit, $offset, $order_by) {
		$this->load->model('mdl_webpages');
		$query = $this->mdl_webpages->get_with_limit($limit, $offset, $order_by);
		return $query;
	}
	
	function get_where($id) {
		$this->load->model('mdl_webpages');
		$query = $this->mdl_webpages->get_where($id);
		return $query;
	}
	
	function get_where_custom($col, $value) {
		$this->load->model('mdl_webpages');
		$query = $this->mdl_webpages->get_where_custom($col, $value);
		return $query;
	}
	
	function _insert($data) {
		$this->load->model('mdl_webpages');
		$this->mdl_webpages->_insert($data);
	}
	
	function _update($id, $data) {
		$this->load->model('mdl_webpages');
		$this->mdl_webpages->_update($id, $data);
	}
	
	function _delete($id) {
		$this->load->model('mdl_webpages');
		$this->mdl_webpages->_delete($id);
	}
	
	function count_where($column, $value) {
		$this->load->model('mdl_webpages');
		$count = $this->mdl_webpages->count_where($column, $value);
		return $count;
	}
	
	function get_max() {
		$this->load->model('mdl_webpages');
		$max_id = $this->mdl_webpages->get_max();
		return $max_id;
	}
	
	function _custom_query($mysql_query) {
		$this->load->model('mdl_webpages');
		$query = $this->mdl_webpages->_custom_query($mysql_query);
		return $query;
	}

}

