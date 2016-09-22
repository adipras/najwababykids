<h1>Manage Homepage Offers Block</h1>
<?php
if(isset($flash)) {
	echo $flash;
}
?>

<p style="margin-top: 30px;">
<?php
	echo anchor('homepage_blocks/create', 'Create New Homepage Offer Block', array('class'=>'btn btn-primary'));
?>
</p>
<div class="row-fluid sortable">		
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white align-justify"></i><span class="break"></span>Existing Homepage Offers Block</h2>
			<div class="box-icon">
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
		<?php
			echo Modules::run('homepage_blocks/_draw_sortable_list');
		?>
		</div>
	</div><!--/span-->
</div><!--/row-->
