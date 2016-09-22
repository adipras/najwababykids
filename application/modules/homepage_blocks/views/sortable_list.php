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
	$this->load->module('homepage_blocks');
	$this->load->module('homepage_offers');
	foreach($query->result() as $row) {
		$param = $row->id;
		$block_title = $row->block_title;
	?>
	<li class="sort" id="<?= $row->id ?>"><i class="icon-sort"></i> <?= $row->block_title ?>
		<?= $block_title ?>
		<?php
			$num_item = $this->homepage_offers->count_where('block_id', $row->id);
				if ($num_item==1) {
					$entity = "Homepage Offer";
				} else {
					$entity = "Homepage Offers";
				}
				echo anchor(base_url(), '<i class="halflings-icon white eye-open"></i>', array('class'=>'btn btn-default'))."&nbsp";
				echo anchor('homepage_blocks/create/'.$param, '<i class="halflings-icon white edit"></i>', array('class'=>'btn btn-info'));
		?>
	</li>
	<?php
	}
	?>
</ul>