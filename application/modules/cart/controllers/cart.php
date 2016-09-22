<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cart extends MX_Controller
{

	function __construct() {
		parent::__construct();
	}

	function _draw_add_to_cart($item_id)
	{
		//fetch the colour option for this item
		$submitted_colour = $this->input->post('submitted_colour', TRUE);
		if($submitted_colour=="") {
			$colour_option[''] = "Select...";
		}

		$this->load->module('store_item_colours');
		$query = $this->store_item_colours->get_where_custom('item_id', $item_id);
		$data['num_colours'] = $query->num_rows();
		foreach ($query->result() as $row) {
			$colour_option[$row->id] = $row->colour;
		}

		//fetch the size option for this item
		$submitted_size = $this->input->post('submitted_size', TRUE);
		if($submitted_size=="") {
			$size_option[''] = "Select...";
		}

		$this->load->module('store_item_sizes');
		$query = $this->store_item_sizes->get_where_custom('item_id', $item_id);
		$data['num_sizes'] = $query->num_rows();
		foreach ($query->result() as $row) {
			$size_option[$row->id] = $row->size;
		}

		$data['submitted_colour'] = $submitted_colour;
		$data['colour_option'] = $colour_option;
		$data['submitted_size'] = $submitted_size;
		$data['size_option'] = $size_option;
		$data['item_id'] = $item_id;
		$this->load->view('add_to_cart', $data);
	}
}

