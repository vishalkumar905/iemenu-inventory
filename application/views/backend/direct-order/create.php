<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createOpeningStockForm" class="form-horizontal" enctype="multipart/form-data" action="#">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>
			<div class="card-content">
				<div class="row col-md-12">
					<span><b>GRN No: </b><?=GRN_SHOR_NAME?>-<?=$grnNumber?><span>
					<span class="pull-right"><b>Date: </b><?=Date('d-m-Y')?></span>
				</div>

				<div class="row">
					<label class="col-md-2 label-on-left">Vendors</label>
					<div class="col-sm-10">
					<?php
						$extra = [
							'id' => 'vendor',
							'class' => 'selectpicker',
							'data-style' => 'select-with-transition select-box-horizontal', 
							'data-live-search' => 'true'
						];

						$selectedVendor = !empty(set_value('vendor')) ? set_value('vendor') : '';

						echo form_dropdown('vendor', $dropdownVendors, $selectedVendor, $extra);
						echo form_error('vendor');
					?>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Categories</label>
					<div class="col-md-10">
						<?php
							echo sprintf("<select id='category' name='category[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>", '');
						?>
					</div>
				</div>
				<?php /*?>
				<div class="row">
					<label class="col-md-2 label-on-left">Store Kitchen</label>
					<div class="col-md-10">
						<?php
							$extra = [
								'id' => 'store',
								'class' => 'selectpicker',
								'data-style' => 'select-with-transition select-box-horizontal', 
								'data-live-search' => 'true'
							];

							// $selectedSubCategory = !empty(set_value('store')) ? set_value('store') : '';

							// echo form_dropdown('store', $dropdownSubCategories, $selectedSubCategory, $extra);
							// echo form_error('store');
						?>
					</div>
				</div>
				<?php */ ?>

				<div class="row">
					<label class="col-md-2 label-on-left">Bill Number*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="billNumber"  required="true" class="form-control" value="<?=!empty(set_value('billNumber')) ? set_value('billNumber') : (isset($billNumber) ? $billNumber : '') ?>">
							<?=form_error('billNumber');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Bill Date*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="date" name="billDate" class="form-control" value="<?=!empty(set_value('billDate')) ? set_value('billDate') :  (isset($billDate) ? $billDate : '')?>">
							<?=form_error('billDate');?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>