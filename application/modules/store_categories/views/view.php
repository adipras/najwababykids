<h1><?= $cat_title ?></h1>
<p><?= $showing_statement ?></p>
<?= $pagination ?>

<div class="row">
<?php
foreach ($query->result() as $row) {
	$small_pic = $row->small_pic;
	$small_pic_path = base_url()."small_pics/".$small_pic;
	$item_page = base_url().$item_segments.$row->item_url;
?>
<div class="col-md-2 img-thumbnail" style="margin: 6px; min-height: 300px">
	<a href="<?= $item_page ?>"><img src="<?= $small_pic_path ?>" title="<?= $row->item_title ?>" class="img-responsive"></a>
	<br>
	<h6><a href="<?= $item_page ?>"><?= $row->item_title ?></a></h6>
	<div style="clear: both; color: red; font-weight: bold;">Rp <?= number_format($row->item_price, 0) ?>
		<?php
		if ($row->was_price>0) { ?>
			<span style="font-weight: normal; color:#999; text-decoration: line-through;">Rp <?= $row->was_price ?></span>
		<?php
		}
		?>
	</div>
</div>
<?php
}
?>
</div>
<?= $pagination ?>
