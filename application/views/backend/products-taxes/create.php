<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="mapProductTaxForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>

			<div class="card-content">
				<div class="row">
					<label class="col-md-2 label-on-left">Select Product*</label>
					<div class="col-sm-10">
						<?php
							if ($updateId > 0 && isset($product))
							{
								echo form_hidden('product', $updateId);
								echo "<label></label>";
								echo "<div class='form-control'>".$dropdownProducts[$product]."</div>";	
							}
							else
							{
								$extra = [
									'id' => 'products',
									'class' => 'selectpicker',
									'data-style' => 'select-with-transition select-box-horizontal',
									'data-live-search' => 'true'
								];

								$selectedProduct = !empty(set_value('product')) ? set_value('product') : (isset($product) ? $product : '');
								echo form_dropdown('product', $dropdownProducts, $selectedProduct, $extra);
							}
						?>
						<?=form_error('product');?>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Choose Tax*</label>
					<div class="col-sm-10">
						<?php
							$extra = [
								'id' => 'tax',
								'class' => 'selectpicker',
								'data-style' => 'select-with-transition select-box-horizontal',
								'data-live-search' => 'true',
							];

							$selectedTax = !empty(set_value('tax[]')) ? set_value('tax[]') : (isset($tax) ? $tax : '');
							echo form_multiselect('tax[]', $dropdownTaxes, $selectedTax, $extra);
						?>
					</div>
				</div>
				<div class="row mt-10">
					<label class="col-sm-2 label-on-left"></label>
					<div class="col-sm-10">
						<button type="submit" name="submit" value="<?=$submitBtn?>"  class="btn btn-rose btn-fill"><?=$submitBtn?><div class="ripple-container"></div></button>
						<button type="submit" name="submit" value="Cancel"  class="btn btn-primary btn-fill">Cancel<div class="ripple-container"></div></button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>