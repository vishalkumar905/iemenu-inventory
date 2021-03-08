<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createRecipeForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>

			<div class="card-content">
				<div class="row">
					<label class="col-md-2 label-on-left">Item Name*</label>
					<div class="col-sm-10">
						<?php
							$extra = [
								'id' => 'menuItem',
								'class' => 'selectpicker',
								'data-style' => 'select-with-transition select-box-horizontal',
								'data-live-search' => 'true'
							];

							$selectedMenuItem = !empty(set_value('menuItem')) ? set_value('menuItem') : (isset($menuItem) ? $menuItem : '');

							if ($updateId > 0 && isset($this->disableUpdateField['menuItem']) && $this->disableUpdateField['menuItem'])
							{
								$extra['disabled'] = true;
								echo form_hidden('menuItem', $selectedMenuItem);
							}

							echo form_dropdown('menuItem', $menuItems, $selectedMenuItem, $extra);
							echo form_error('menuItem');
						?>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Recipes*</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-4">
								<select id='product' name='product[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>
							</div>
							<div class="col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Quantity*</label>
									<input type="text" name="quantity" required="true" class="form-control" value="<?=!empty(set_value('quantity')) ? set_value('quantity') : (isset($quantity) ? $quantity : '') ?>">
									<?=form_error('quantity');?>
								</div>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<select id='product' name='product[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>
							</div>
							<div class="col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Quantity*</label>
									<input type="text" name="quantity" required="true" class="form-control" value="<?=!empty(set_value('quantity')) ? set_value('quantity') : (isset($quantity) ? $quantity : '') ?>">
									<?=form_error('quantity');?>
								</div>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-primary btn-round btn-fab btn-fab-mini"><i class="material-icons">clear</i></button>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4">
								<select id='product' name='product[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>
							</div>
							<div class="col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Quantity*</label>
									<input type="text" name="quantity" required="true" class="form-control" value="<?=!empty(set_value('quantity')) ? set_value('quantity') : (isset($quantity) ? $quantity : '') ?>">
									<?=form_error('quantity');?>
								</div>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-primary btn-round btn-fab btn-fab-mini"><i class="material-icons">clear</i></button>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4">
								<select id='product' name='product[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>
							</div>
							<div class="col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Quantity*</label>
									<input type="text" name="quantity" required="true" class="form-control" value="<?=!empty(set_value('quantity')) ? set_value('quantity') : (isset($quantity) ? $quantity : '') ?>">
									<?=form_error('quantity');?>
								</div>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-primary btn-round btn-fab btn-fab-mini"><i class="material-icons">clear</i></button>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-10">
					<label class="col-sm-2 label-on-left"></label>
					<div class="col-sm-10">
						<button type="submit" name="submit" value="<?=$submitBtn?>"  class="btn btn-rose"><?=$submitBtn?><div class="ripple-container"></div></button>
						<button type="submit" name="submit" value="Cancel"  class="btn btn-primary btn-fill">Cancel<div class="ripple-container"></div></button>
						<!-- <a href="#importExcelForm"><b>Upload Excel Sheet</b></a> -->
					</div>
				</div>
			</div>
		</form>
	</div>
</div>