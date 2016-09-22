<h1>Manage Categories</h1>
<?php
if(isset($flash)) {
	echo $flash;
}
?>

<p style="margin-top: 30px;">
<?php
	echo anchor('store_categories/create', 'Create New Category', array('class'=>'btn btn-primary'));
?>
</p>
<div class="row-fluid sortable">		
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white align-justify"></i><span class="break"></span>Existing Categories</h2>
			<div class="box-icon">
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
		<?php
			echo Modules::run('store_categories/_draw_sortable_list', $parent_cat_id);
		?>
		</div>
	</div><!--/span-->
</div><!--/row-->
