<h1>Content Management System</h1>
<?php
if(isset($flash)) {
	echo $flash;
}
?>

<p style="margin-top: 30px;">
<?php
	echo anchor('webpages/create', 'Create New Webpage', array('class'=>'btn btn-primary'));
?>
</p>
<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white file"></i><span class="break"></span>Custom Webpages</h2>
						<div class="box-icon">
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Page URL</th>
								  <th>Page Title</th>
								  <th class="span2">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
						  <?php
						  foreach($query->result() as $row) {
						  	$param = $row->id;
						  ?>
							<tr>
								<td><?= base_url().$row->page_url ?></td>
								<td class="center"><?= $row->page_title ?></td>
								<td class="center">
									<?php
									echo anchor($row->page_url, '<i class="halflings-icon white zoom-in"></i>', array('class'=>'btn btn-success'))."&nbsp";
									echo anchor('webpages/create/'.$param, '<i class="halflings-icon white edit"></i>', array('class'=>'btn btn-info'));
									?>
								</td>
							</tr>
							<?php } ?>
			
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
