<div style="background-color: #ddd; border-radius: 7px; margin-top: 24px; padding: 7px;">
	<table class="table">
		<tr>
			<td>Item ID: </td>
			<td><?= $item_id ?></td>
		</tr>

		<?php
		if($num_colours>0) { ?>
		<tr>
			<td>Colour: </td>
			<td>
				<?php
					$additional_dd_code = 'class="form-control"';
					echo form_dropdown('status', $colour_option, $submitted_colour, $additional_dd_code);
				?>
			</td>
		</tr>
		<?php
		}
		?>
		<?php
		if($num_sizes>0) { ?>
		<tr>
			<td>Size: </td>
			<td>
				<?php
					$additional_dd_code = 'class="form-control"';
					echo form_dropdown('size', $size_option, $submitted_size, $additional_dd_code);
				?>
			</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td>Qty: </td>
			<td>
				<div class="col-md-5" style="padding-left: 0px">
					<input type="text" class="form-control">
				</div>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add To Basket</button>
			</td>
		</tr>
	</table>
</div>