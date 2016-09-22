<h1>Manage Accounts</h1>
<?php
if(isset($flash)) {
	echo $flash;
}
?>

<p style="margin-top: 30px;">
<?php
	echo anchor('store_accounts/create', 'Create New Account', array('class'=>'btn btn-primary'));
?>
</p>
<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white briefcase"></i><span class="break"></span> Customer Accounts</h2>
						<div class="box-icon">
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Username</th>
								  <th>First Name</th>
								  <th>Last Name</th>
								  <th>Company</th>
								  <th>Date Created</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
						  <?php
						  $this->load->module('timedate');
						  foreach($query->result() as $row) {
						  	$param = $row->id;
						  	$date_created = $this->timedate->get_nice_date($row->date_made, 'indodesia');
						  ?>
							<tr>
								<td><?= $row->username ?></td>
								<td><?= $row->firstname ?></td>
								<td class="center"><?= $row->lastname ?></td>
								<td class="center"><?= $row->company ?></td>
								<td class="center"><?= $date_created ?></span></td>
								<td class="center">
									<?php
									echo anchor('store_accounts/view/'.$param, '<i class="halflings-icon white eye-open"></i>', array('class'=>'btn btn-success'));
									echo anchor('store_accounts/create/'.$param, '<i class="halflings-icon white edit"></i>', array('class'=>'btn btn-info'));
									?>
								</td>
							</tr>
							<?php } ?>
			
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
