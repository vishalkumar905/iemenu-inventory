<div class="col-md-12">
	<div class="card mt-0">
		<form id="createReportForm" class="form-horizontal" enctype="multipart/form-data">
			<div class="card-content">

				<div class="row">
					<label class="col-md-2 label-on-left">Start Date*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="text" name="startDate" id="startDate" class="form-control" value="<?=!empty(set_value('startDate')) ? set_value('startDate') :  (isset($startDate) ? $startDate : '')?>">
							<?=form_error('startDate');?>
						</div>
					</div>
				</div>

				<div class="row">
					<label class="col-md-2 label-on-left">End Date*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="text" name="endDate" id="endDate" class="form-control" value="<?=!empty(set_value('endDate')) ? set_value('endDate') :  (isset($endDate) ? $endDate : '')?>">
							<?=form_error('endDate');?>
						</div>
					</div>
				</div>

				<div class="row mt-10">
					<label class="col-sm-2 label-on-left"></label>
					<div class="col-sm-10">
						<button type="button" name="submit" value="Search" id="search"  class="btn btn-rose btn-fill">Search<div class="ripple-container"></div></button>
					</div>
				</div>

				<?php /* ?>
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
			</div>
		</form>
	</div>
</div>