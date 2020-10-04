<div class="row">
	<div class="col-md-12">
		<div class="card mt-0">
			<form method="post" class="form-horizontal" action="<?=current_url()?>">
				<div class="card-header card-header-text">
					<h4 class="card-title">Add Product Information</h4>
				</div>
				<div class="card-content">
					<div class="row">
						<label class="col-md-2 label-on-left">Product Code</label>
						<div class="col-sm-10">
							<div class="form-group label-floating">
								<label class="control-label"></label>
								<input type="text" name="productCode" class="form-control" value="<?=!empty(set_value('productCode')) ? set_value('productCode') : ''?>">
								<?=form_error('productCode');?>
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-md-2 label-on-left">Product Name</label>
						<div class="col-sm-10">
							<div class="form-group label-floating">
								<label class="control-label"></label>
								<input type="text" name="productName" class="form-control" value="<?=!empty(set_value('productName')) ? set_value('productName') : ''?>">
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

										array_splice($categories, 0, 0, ['' => 'Choose category']);

										echo form_dropdown('category', $categories, '', $extra);
									?>
									
									<?=form_error('category');?>
								</div>

								<?php if (!empty($subCategories)){ ?>
								<div class="col-md-4">
									<select class="selectpicker" data-style="select-with-transition select-box-horizontal">
										<option disabled selected>Choose category</option>
										<option value="2">Foobar</option>
										<option value="3">Is great</option>
									</select>
									<?=form_error('category');?>
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
						<label class="col-md-2 label-on-left">Image</label>
						<div class="col-sm-10">
							<div class="form-group label-floating">
								<div class="fileinput fileinput-new text-center" data-provides="fileinput">
									<div class="fileinput-new thumbnail">
										<img src="<?=base_url()?>assets/img/image_placeholder.jpg" alt="...">
									</div>
									<div class="fileinput-preview fileinput-exists thumbnail"></div>
									<div class="display-inline">
										<span class="btn btn-info btn-round btn-file">
											<span class="fileinput-new">Product picture</span>
											<span class="fileinput-exists">Change</span>
											<input type="file" name="..." />
										</span>
										<a href="extended.html#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-md-2 label-on-left">HSN Code</label>
						<div class="col-sm-10">
							<div class="form-group label-floating">
								<label class="control-label"></label>
								<input type="text" name="hsnCode" class="form-control" value="<?=!empty(set_value('hsnCode')) ? set_value('hsnCode') : ''?>">
								<?=form_error('hsnCode');?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="shelfLife" value="1" <?=!empty(set_value('shelfLife')) ? "checked" : ''?>>Self life
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-left"></label>
						<div class="col-sm-10">
							<button type="submit" name="submit" value="Save"  class="btn btn-rose btn-fill">Save<div class="ripple-container"></div></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>