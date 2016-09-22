<style type="text/css">
	.sort {
		list-style-type: none;
		border: 1px #aaa solid;
		color: #333;
		padding: 10px;
		margin-bottom: 4px;
	}
</style>

<ul id="sortlist">
	<?php
	$this->load->module('store_categories');
	foreach($query->result() as $row) {
		$num_sub_cats = $this->store_categories->_count_sub_cats($row->id);
		$param = $row->id;

		if($row->parent_cat_id==0) {
			$parent_cat_title = "&nbsp;";
		} else {
			$parent_cat_title = $this->store_categories->_get_cat_title($row->parent_cat_id);						  		
	}
	?>
	<li class="sort" id="<?= $row->id ?>"><i class="icon-sort"></i> <?= $row->cat_title ?>
		<?= $parent_cat_title ?>
		<?php
			if ($num_sub_cats<1) {
				echo "&nbsp;";
			} else {
				if ($num_sub_cats==1) {
					$entity = "Category";
				} else {
					$entity = "Categories";
				}
				echo anchor('store_categories/manage/'.$param, '<i class="halflings-icon white eye-open"></i> '.$num_sub_cats.' Sub '.$entity, array('class'=>'btn btn-default'))."&nbsp";
				echo anchor('store_categories/create/'.$param, '<i class="halflings-icon white edit"></i>', array('class'=>'btn btn-info'));
			}
		?>
	</li>
	<?php
	}
	?>
</ul>