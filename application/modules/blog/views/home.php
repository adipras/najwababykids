<h1>Update Blog</h1>
<?php
if(isset($flash)) {
	echo $flash;
}
?>

<p style="margin-top: 30px;">
<?php
	echo anchor('blog/create', 'Create New Blog Entry', array('class'=>'btn btn-primary'));
?>
</p>
<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white file"></i><span class="break"></span>Custom Blog</h2>
						<div class="box-icon">
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
							  	  <th>Picture</th>
							  	  <th>Date Published</th>
							  	  <th>Author</th>
								  <th>Blog URL</th>
								  <th>Blog Headline</th>
								  <th class="span2">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
						  <?php
						  $this->load->module('timedate');
						  foreach($query->result() as $row) {
						  	$param = $row->id;
						  	$date_published = $this->timedate->get_nice_date($row->date_published, 'indodesia_short');
						  	$picture = $row->picture;
						  	$thumbnail_name = str_replace('.', '_thumb.', $picture);
						  	$thumbnail_path = base_url().'blog_pics/'.$thumbnail_name;
						  ?>
							<tr>
								<td><img src="<?= $thumbnail_path ?>"></td>
								<td style="white-space:nowrap"><?= $date_published ?></td>
								<td><?= $row->author ?></td>
								<td><?= base_url().$row->page_url ?></td>
								<td class="center"><?= $row->page_title ?></td>
								<td class="center">
									<?php
									echo anchor($row->page_url, '<i class="halflings-icon white zoom-in"></i>', array('class'=>'btn btn-success'))."&nbsp";
									echo anchor('blog/create/'.$param, '<i class="halflings-icon white edit"></i>', array('class'=>'btn btn-info'));
									?>
								</td>
							</tr>
							<?php } ?>
			
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
