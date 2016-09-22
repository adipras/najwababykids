<?php
foreach ($query->result() as $row) {
	$small_pic = $row->small_pic;
	$small_pic_path = base_url()."small_pics/".$small_pic;
	$item_page = base_url().$item_segments.$row->item_url;
	$item_price = number_format($row->item_price, 0);
	$item_price = str_replace(',', '.', $item_price);
?>
<div class="col-xs-3">
    <div class="offer offer-radius offer-<?= $theme ?>" style="min-height: 400px;">
        <div class="shape">
            <div class="shape-text">
              <span class="glyphicon glyphicon-star" aria-hidden="true" style="font-size: 1.4em;"></span>               
            </div>
        </div>
        <div class="offer-content">
            <h3 class="lead">
              Hanya Rp<b><?= $item_price ?></b>
            </h3>
			<a href="<?= $item_page ?>"><img src="<?= $small_pic_path ?>" title="<?= $row->item_title ?>" class="img-responsive"></a>
			<br>                       
            <p>
              <a href="<?= $item_page ?>"><?= $row->item_title ?></a>
            </p>
        </div>
    </div>
</div>
<?php
}