<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createRecipeForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>

			<div class="card-content">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="label-control">Item Name*</label>
							<?php
								$extra = [
									'id' => 'menuItem',
									'class' => 'selectpicker mt-0',
									'required' => 'true',
									'data-style' => 'select-with-transition select-box-horizontal',
									'data-live-search' => 'true'
								];
								
								$selectedMenuItem = !empty(set_value('menuItem')) ? set_value('menuItem') : (isset($menuItem) ? $menuItem : '');

								if ($updateId > 0 && isset($this->disableUpdateField['menuItem']) && $this->disableUpdateField['menuItem'])
								{
									$extra['disabled'] = true;
									echo form_hidden('menuItem', $selectedMenuItem);
								}

								echo form_dropdown('menuItem', [], $selectedMenuItem, $extra);
								echo form_error('menuItem');
							?>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label class="label-control">Item Qty*</label>
							<input type="number" min="0" name="menuItemQty" id="menuItemQty" class="form-control" required/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class='col-md-12'>
						<div class="form-group">		
							<label class="label-control">Recipes*</label>
							<div class="row" id="menuItemRecipe-1">
								<div class="col-sm-4">
									<select id="menuItemProduct-1" name="menuItemProduct-1" class='selectpicker' data-style='select-with-transition select-box-horizontal' data-live-search='true'></select>
								</div>
								<div class="col-sm-4">
									<select id="menuItemProductSiUnit-1" name="menuItemProductSiUnit-1" class='selectpicker' data-style='select-with-transition select-box-horizontal' data-live-search='true'></select>
								</div>
								<div class="col-sm-2">
									<div class="form-group label-floating">
										<label class="control-label">Quantity*</label>
										<input type="text" id="menuItemProductQty-1" id="menuItemProductQty-1" required="true" class="form-control"/>
									</div>
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-success btn-round btn-fab btn-fab-mini" id="addNewMenuItemRecipe"><i class="material-icons">add</i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
							


				<div class="row mt-10">
					<div class="col-sm-12">
						<button type="submit" name="submit" value="<?=$submitBtn?>" id="saveRecipe" class="btn btn-rose"><?=$submitBtn?><div class="ripple-container"></div></button>
						<button type="submit" name="submit" value="Cancel"  class="btn btn-primary btn-fill">Cancel<div class="ripple-container"></div></button>
						<!-- <a href="#importExcelForm"><b>Upload Excel Sheet</b></a> -->
					</div>
				</div>
			</div>
		</form>
	</div>
</div>