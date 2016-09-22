<?php
function get_theme($count) {
  switch ($count) {
    case '1':
      $theme = 'danger';
      break;
    case '2':
      $theme = 'warning';
      break;
    case '3':
      $theme = 'primary';
      break;
    case '4':
      $theme = 'success';
      break;
    
    default:
      $theme = 'primary';
      break;
  }
  return $theme;
}
?>

<link href="<?php echo base_url(); ?>assets/css/offers.css" rel="stylesheet">

<?php
$count = 0;
$this->load->module('homepage_offers');
$this->load->module('site_settings');
$item_segments = $this->site_settings->_get_item_segments();
foreach ($query->result() as $row) {
  $count++;
  $block_id = $row->id;
  $num_items_on_block = $this->homepage_offers->count_where('block_id', $block_id);
  if ($num_items_on_block>0) {
    if ($count>4) {
      $count = 1;
    }
    $theme = get_theme($count);
  ?>
  <div class="panel panel-<?= $theme ?>">
    <div class="panel-heading">
      <h3 class="panel-title"><?= $row->block_title ?></h3>
    </div>
    <div class="panel-body">
      <div class="row">
      <?php
      $block_data['block_id'] = $block_id;
      $block_data['theme'] = $theme;
      $block_data['item_segments'] = $item_segments;
      $this->homepage_offers->_draw_offers($block_data);
      ?>
      </div>
    </div>
  </div>
  <?php
  }
}
/*
<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">Panel title</h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Panel title</h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-warning">
  <div class="panel-heading">
    <h3 class="panel-title">Panel title</h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title">Panel title</h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>
*/
?>
