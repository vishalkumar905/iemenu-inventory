<div class="col-md-4">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createVendorForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>
			<div class="card-content">
				<div class="form-group label-floating">
					<label class="">Vendors*</label>
					<?php
						$extra = [
							'id' => 'vendor',
							'class' => 'selectpicker',
							'data-style' => 'select-with-transition select-box-horizontal', 
						];

						$selectedVendor = !empty(set_value('vendor')) ? set_value('vendor') : '';

						echo form_dropdown('vendor', $dropdownVendors, $selectedVendor, $extra);
						echo form_error('vendor');
					?>
				</div>
				<div class="form-group label-floating">
					<label class="">Category*</label>
					<?php
						$extra = [
							'id' => 'category',
							'class' => 'selectpicker',
							'data-style' => 'select-with-transition select-box-horizontal', 
						];

						$selectedCategory = !empty(set_value('category')) ? set_value('category') : '';

						echo form_dropdown('category', $dropdownCategories, $selectedCategory, $extra);
						echo form_error('category');
					?>
				</div>

				<?php if (!empty($dropdownSubCategories)){ ?>
				<div class="form-group label-floating">
					<label class="">Sub Category*</label>
					<?php
						$extra = [
							'id' => 'subCategory',
							'class' => 'selectpicker',
							'data-style' => 'select-with-transition select-box-horizontal', 
							'data-live-search' => 'true'
						];

						$selectedSubCategory = !empty(set_value('subCategory')) ? set_value('subCategory') : '';
						echo form_dropdown('subCategory', $dropdownSubCategories, $selectedSubCategory, $extra);
					?>
				</div>
				<?php } else { ?>
				<div class="form-group label-floating" id="subCategoryBox" style="display: none;">
					<label class="">Sub Category*</label>
					<select class="selectpicker" id="subCategory" data-live-search = 'true' name="subCategory" data-style="select-with-transition select-box-horizontal">
						
					</select>
				</div>
				<?php } ?>

				<div class="form-group label-floating">
					<label class="">Product*</label>
					<select class="selectpicker" id="product" data-live-search = 'true' name="product" data-style="select-with-transition select-box-horizontal">
						<option>Choose Product</option>
					</select>
					<?=form_error('product');?>
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