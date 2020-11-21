<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createProductForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>

			<div class="card-content">
				<div class="row">
					<label class="col-md-2 label-on-left">Product Code*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="productCode" required="true" class="form-control" value="<?=!empty(set_value('productCode')) ? set_value('productCode') : (isset($productCode) ? $productCode : '') ?>">
							<?=form_error('productCode');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Product Name*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="productName"  required="true" class="form-control" value="<?=!empty(set_value('productName')) ? set_value('productName') : (isset($productName) ? $productName : '') ?>">
							<?=form_error('productName');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Category*</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-md-4">
								<?php
									$extra = [
										'id' => 'categories',
										'class' => 'selectpicker',
										'data-style' => 'select-with-transition select-box-horizontal',
										'data-live-search' => 'true'
									];

									$selectedCategory = !empty(set_value('category')) ? set_value('category') : (isset($category) ? $category : '');

									if ($updateId > 0)
									{
										$extra['disabled'] = true;
										echo form_hidden('category', $selectedCategory);
									}

									echo form_dropdown('category', $categories, $selectedCategory, $extra);
								?>
							</div>
							<?php if (!empty($subCategories)){ ?>
							<div class="col-md-4"  id="subCategoryBox">
								<?php
									$extra = [
										'id' => 'subCategory',
										'class' => 'selectpicker',
										'data-style' => 'select-with-transition select-box-horizontal', 
										'data-live-search' => 'true'
									];

									$selectedSubCategory = !empty(set_value('subCategory')) ? set_value('subCategory') : (isset($subCategory) ? $subCategory : '');
									if ($updateId > 0)
									{
										$extra['disabled'] = true;
										echo form_hidden('subCategory', $selectedSubCategory);
									}

									echo form_dropdown('subCategory', $subCategories, $selectedSubCategory, $extra);
								?>
							</div>
							<?php } else { ?>
							<div class="col-md-4" id="subCategoryBox" style="display: none;">
								<select class="selectpicker" id="subCategory" data-live-search = 'true' name="subCategory" data-style="select-with-transition select-box-horizontal">
									
								</select>
							</div>
							<?php } ?>

							<?php if($updateId == 0) { ?>
							<div class="col-md-4">
								<div class="form-group label-floating">
									<label class="control-label"></label>
									<input type="text" name="subCategoryName" class="form-control" placeholder="Category name" value="<?=!empty(set_value('subCategoryName')) ? set_value('subCategoryName') : ''?>">
								</div>
							</div>
							<?php } ?>
							<div class="col-md-12"><?=form_error('category');?></div>
						</div>

					</div>
				</div>
				<div class="row">

					<label class="col-md-2 label-on-left">Unit*</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-md-4">
								<?php
									$extra = [
										'id' => 'baseUnit',
										'class' => 'selectpicker',
										'data-style' => 'select-with-transition select-box-horizontal', 
									];
									
									if ($updateId > 0)
									{
										$extra['disabled'] = true;
										echo form_hidden('baseUnit', $baseUnit);
									}

									$selectedBaseUnit = !empty(set_value('baseUnit')) ? set_value('baseUnit') : (isset($baseUnit) ? $baseUnit : '');
									echo form_dropdown('baseUnit', $baseUnits, $selectedBaseUnit, $extra);
									echo form_error('baseUnit');
								?>
							</div>
							<?php if (!empty($siUnits)){ ?>
							<div class="col-md-8"  id="siUnitBox">
								<?php
									$extra = [
										'id' => 'siUnit',
										'class' => 'selectpicker',
										'data-style' => 'select-with-transition select-box-horizontal', 
										'data-live-search' => 'true',
									];

									if ($updateId > 0)
									{
										// $extra['disabled'] = true;
										echo form_hidden('siUnit', $siUnit);
									}


									$selectedSiUnit = !empty(set_value('siUnit')) ? set_value('siUnit') : (isset($siUnit) ? $siUnit : '--');
									echo form_multiselect('siUnit[]', $siUnits, $selectedSiUnit, $extra);
									echo form_error('siUnit');
								?>
							</div>
							<?php } else { ?>
							<div class="col-md-8" id="siUnitBox" style="display: none;">
								<select class="selectpicker" multiple id="siUnit" name="siUnit[]" data-style="select-with-transition select-box-horizontal" data-live-search = 'true'>
									
								</select>
							</div>
							<?php } ?>
							<div class="col-md-12"><?=form_error('siUnit');?></div>

						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Product Type*</label>
					<div class="col-sm-10">
						<?php
							$extra = [
								'class' => 'selectpicker',
								'data-style' => 'select-with-transition select-box-horizontal', 
							];

							$selectedProudctType = !empty(set_value('productType')) ? set_value('productType') : (isset($productType) ? $productType : '');
							
							if ($updateId > 0)
							{
								$extra['disabled'] = true;
								echo form_hidden('productType', $selectedProudctType);
							}

							echo form_dropdown('productType', $productTypes, $selectedProudctType, $extra);
							echo form_error('productType');
						?>
					</div>
				</div>

				<div class="row">
					<label class="col-md-2 label-on-left">HSN Code</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="hsnCode" class="form-control" value="<?=!empty(set_value('hsnCode')) ? set_value('hsnCode') :  (isset($hsnCode) ? $hsnCode : '')?>">
							<?=form_error('hsnCode');?>
						</div>
					</div>
				</div>
				
				<?php if ($this->productImageUpload) { ?>
				<div class="row">
					<label class="col-md-2 label-on-left">Image*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<div class="fileinput fileinput-new text-center" data-provides="fileinput">
								<div class="fileinput-new thumbnail">
									<img src="<?=$productImage?>" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail"></div>
								<div class="display-inline">
									<span class="btn btn-info btn-round btn-file">
										<span class="fileinput-new">Product picture</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="productImage" />
									</span>
									<a href="extended.html#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
								</div>
							</div>
							<?=isset($proudctImageUploadError) ? $proudctImageUploadError : ''?>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
						<div class="checkbox">
							<label>
								<?=form_hidden('shelfLife', 0)?>
								<input type="checkbox" name="shelfLife" value="1" <?=!empty(set_value('shelfLife')) ? "checked" : (isset($shelfLife) && $shelfLife == 1 ? 'checked' : '')?>>Self life
							</label>
						</div>
					</div>
				</div>
				<div class="row mt-10">
					<label class="col-sm-2 label-on-left"></label>
					<div class="col-sm-10">
						<button type="submit" name="submit" value="<?=$submitBtn?>"  class="btn btn-rose btn-fill"><?=$submitBtn?><div class="ripple-container"></div></button>
						<button type="submit" name="submit" value="Cancel"  class="btn btn-primary btn-fill">Cancel<div class="ripple-container"></div></button>
						<a href="#importExcelForm"><b>Upload Excel Sheet</b></a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>