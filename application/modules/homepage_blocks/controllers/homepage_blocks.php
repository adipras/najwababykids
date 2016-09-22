<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Homepage_blocks extends MX_Controller
{

	function __construct() {
		parent::__construct();
	}

    function manage() {
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $data['sort_this'] = TRUE;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "home";
        echo Modules::run('templates/admin', $data);
    }

    function create() {
        $this->load->library('session');
        $update_id = $this->uri->segment(3);
        $submit = $this->input->post('submit', TRUE);

        if($submit=="Cancel") {
            redirect('homepage_blocks/manage');
        }

        if($submit=="Submit") {
            //process the form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('block_title', 'Homepage Offer Title', 'required|max_length[240]');

            if($this->form_validation->run() == TRUE) {
                //get the variables
                $data = $this->fetch_data_from_post();

                if(is_numeric($update_id)) {
                    //update the homepage offer details
                    $this->_update($update_id, $data);
                    $flash_msg = "The homepage offer details were successfully update.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('homepage_blocks/create/'.$update_id);
                } else {
                    //insert a new homepage offer
                    $this->_insert($data);
                    $update_id = $this->get_max();//get the ID of the new homepage offer
                    $flash_msg = "The homepage offer was successfully added.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('homepage_blocks/create/'.$update_id);
                }
            }
        }

        if((is_numeric($update_id)) && ($submit!="Submit")) {
            $data = $this->fetch_data_from_db($update_id);
        }else {
            $data = $this->fetch_data_from_post();
        }

        if(!is_numeric($update_id)) {
            $data['headline'] = "Create New Homepage Offer";
        } else {
            $block_title = $this->_get_block_title($update_id);
            $data['headline'] = "Update ".$block_title;
        }

        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "create";
        echo Modules::run('templates/admin', $data);
    }

    function _get_block_title($update_id) {
    	$data = $this->fetch_data_from_db($update_id);
    	$block_title = $data['block_title'];
    	return $block_title;
    }

    function _draw_sortable_list() {
        $mysql_query = "select * from homepage_blocks order by priority";
        $data['query'] = $this->_custom_query($mysql_query);
        $this->load->view('sortable_list', $data);
    }

    function sort() {
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $number = $this->input->post('number', TRUE);
        for ($i=1; $i <= $number; $i++) { 
            $update_id = $_POST['order'.$i];
            $data['priority'] = $i;
            $this->_update($update_id, $data);    
        }        
    }

    function view($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }

        $this->load->module('site_settings');
        $this->load->module('custom_pagination');

        //fetch the item details
        $data = $this->fetch_data_from_db($update_id);

        //count the items that belong to this homepage offer
        $use_limit = FALSE;
        $mysql_query = $this->_generate_mysql_query($update_id, $use_limit);
        $query = $this->_custom_query($mysql_query);
        $total_items = $query->num_rows();

        //fetch the items for this page
        $use_limit = TRUE;
        $mysql_query = $this->_generate_mysql_query($update_id, $use_limit);

        $pagination_data['template'] = 'public_bootstrap';
        $pagination_data['target_base_url'] = $this->get_target_pagination_base_url();
        $pagination_data['total_rows'] = $total_items;
        $pagination_data['offset_segment'] = 4;
        $pagination_data['limit'] = $this->get_limit();
        $pagination_data['offset'] = $this->get_offset();

        $data['showing_statement'] = $this->custom_pagination->get_showing_statement($pagination_data);
        $data['pagination'] = $this->custom_pagination->_generate_pagination($pagination_data);
        $data['item_segments'] = $this->site_settings->_get_item_segments();
        $data['query'] = $this->_custom_query($mysql_query);
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['module'] =  "homepage_blocks";
        $data['view_file'] = "view";
        echo Modules::run('templates/public_bootstrap', $data);
    }

    function get_target_pagination_base_url() {
        $first_bit = $this->uri->segment(1);
        $second_bit = $this->uri->segment(2);
        $third_bit = $this->uri->segment(3);
        $target_base_url = base_url().$first_bit."/".$second_bit."/".$third_bit;
        return $target_base_url;
    }

    function _draw_blocks() {
        //draw the offer blocks that are on the hompage
        $data['query'] = $this->get('priority');
        $num_rows = $data['query']->num_rows();
        if ($num_rows>0) {
            $this->load->view('homepage_blocks', $data);
        }
    }

    function _generate_mysql_query($update_id, $use_limit) {
        //NOTE: use_limit can be TRUE or FALSE
        $mysql_query = "SELECT si.item_title, si.item_price, si.small_pic, si.was_price, si.item_url
                        FROM store_cat_assign sca
                            INNER JOIN store_items si ON sca.item_id = si.id
                        WHERE sca.cat_id=$update_id AND si.status=1";

        if($use_limit==TRUE) {
            $limit = $this->get_limit();
            $offset = $this->get_offset();
            $mysql_query.= " limit ".$offset.", ".$limit;
        }
        return $mysql_query;
    }

    function deleteconf($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }
           
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $data['headline'] = "Delete Entire Offer Block";
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
            redirect('homepage_blocks/create/'.$update_id);
        } elseif ($submit=="Yes - Delete Offer Block") {
            $this->_process_delete($update_id);

            $flash_msg = "The offer block was successfully deleted.";
            $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
            $this->session->set_flashdata('item', $value);

            redirect('homepage_blocks/manage');
        }
    }

    function _process_delete($update_id) {
        //delete any items that are associated with this offer block
        $mysql_query = "delete from homepage_offers where block_id=$update_id";
        $query = $this->_custom_query($mysql_query);

        //delete the page
        $this->_delete($update_id);
    }

    function get_limit() {
        $limit = 20;
        return $limit;
    }

    function get_offset() {
        $offset = $this->uri->segment(4);
        if(!is_numeric($offset)) {
            $offset = 0;
        }
        return $offset;
    }

    function fetch_data_from_post() {
        $data['block_title'] = $this->input->post('block_title', TRUE);
        return $data;
    }

    function fetch_data_from_db($update_id) {
        if(!is_numeric($update_id)) {
            redirect('site_security/not_allowed');
        }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['block_title'] = $row->block_title;
        }
        if(!isset($data)) {
            $data = "";
        }

        return $data;
    }
    /*
    function fix () {
        $query = $this->get('id');
        foreach ($query->result() as $row) {
            $data['cat_url'] = url_title($row->block_title);
            $this->_update($row->id, $data);
        }
        echo "All Finished";
    }
    */
	
	function get($order_by) {
		$this->load->model('mdl_homepage_blocks');
		$query = $this->mdl_homepage_blocks->get($order_by);
		return $query;
	}
	
	function get_with_limit($limit, $offset, $order_by) {
		$this->load->model('mdl_homepage_blocks');
		$query = $this->mdl_homepage_blocks->get_with_limit($limit, $offset, $order_by);
		return $query;
	}
	
	function get_where($id) {
		$this->load->model('mdl_homepage_blocks');
		$query = $this->mdl_homepage_blocks->get_where($id);
		return $query;
	}
	
	function get_where_custom($col, $value) {
		$this->load->model('mdl_homepage_blocks');
		$query = $this->mdl_homepage_blocks->get_where_custom($col, $value);
		return $query;
	}
	
	function _insert($data) {
		$this->load->model('mdl_homepage_blocks');
		$this->mdl_homepage_blocks->_insert($data);
	}
	
	function _update($id, $data) {
		$this->load->model('mdl_homepage_blocks');
		$this->mdl_homepage_blocks->_update($id, $data);
	}
	
	function _delete($id) {
		$this->load->model('mdl_homepage_blocks');
		$this->mdl_homepage_blocks->_delete($id);
	}
	
	function count_where($column, $value) {
		$this->load->model('mdl_homepage_blocks');
		$count = $this->mdl_homepage_blocks->count_where($column, $value);
		return $count;
	}
	
	function get_max() {
		$this->load->model('mdl_homepage_blocks');
		$max_id = $this->mdl_homepage_blocks->get_max();
		return $max_id;
	}
	
	function _custom_query($mysql_query) {
		$this->load->model('mdl_homepage_blocks');
		$query = $this->mdl_homepage_blocks->_custom_query($mysql_query);
		return $query;
	}

}

