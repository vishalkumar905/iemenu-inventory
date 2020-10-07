<div class="row" id="productPageContainer">
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
						<label class="col-md-2 label-on-left">Category</label>
						<div class="col-sm-10">
							<div class="row">
								<div class="col-md-4">
									<?php
										$extra = [
											'id' => 'categories',
											'class' => 'selectpicker',
											'data-style' => 'select-with-transition select-box-horizontal', 
										];

										$selectedCategory = !empty(set_value('category')) ? set_value('category') : (isset($category) ? $category : '');
										echo form_dropdown('category', $categories, $selectedCategory, $extra);
										echo form_error('category');
									?>
								</div>
								<?php if (!empty($subCategories)){ ?>
								<div class="col-md-4">
									<?php
										$extra = [
											'id' => 'subCategories',
											'class' => 'selectpicker',
											'data-style' => 'select-with-transition select-box-horizontal', 
										];

										$selectedSubCategory = !empty(set_value('subCategory')) ? set_value('subCategory') : (isset($subCategory) ? $subCategory : '');
										echo form_dropdown('subCategory', $subCategories, $selectedSubCategory, $extra);
										echo form_error('subCategory');
									?>
								</div>
								<?php } else { ?>
								<div class="col-md-4" id="subCategoryBox" style="display: none;">
									<select class="selectpicker" id="subCategory" name="subCategory" data-style="select-with-transition select-box-horizontal">
										
									</select>
								</div>
								<?php } ?>

								<div class="col-md-4">
									<div class="form-group label-floating">
										<label class="control-label"></label>
										<input type="text" name="subCategoryName" class="form-control" placeholder="Category name" value="<?=!empty(set_value('subCategoryName')) ? set_value('subCategoryName') : ''?>">
										<?=form_error('subCategoryName');?>
									</div>
								</div>
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
								echo form_dropdown('productType', $productTypes, $selectedProudctType, $extra);
								echo form_error('productType');
							?>
						</div>
					</div>

					<div class="row">
						<label class="col-md-2 label-on-left">HSN Code*</label>
						<div class="col-sm-10">
							<div class="form-group label-floating">
								<label class="control-label"></label>
								<input type="text" name="hsnCode" class="form-control" required="true" value="<?=!empty(set_value('hsnCode')) ? set_value('hsnCode') :  (isset($hsnCode) ? $hsnCode : '')?>">
								<?=form_error('hsnCode');?>
							</div>
						</div>
					</div>

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

					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-10">
							<div class="checkbox">
								<label>
									<?=form_hidden('shelfLife', 0)?>
									<input type="checkbox" name="shelfLife" value="1" <?=!empty(set_value('shelfLife')) ? "checked" : ''?>>Self life
								</label>
							</div>
						</div>
					</div>
					<div class="row mt-10">
						<label class="col-sm-2 label-on-left"></label>
						<div class="col-sm-10">
							<button type="submit" name="submit" value="<?=$submitBtn?>"  class="btn btn-rose btn-fill"><?=$submitBtn?><div class="ripple-container"></div></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>