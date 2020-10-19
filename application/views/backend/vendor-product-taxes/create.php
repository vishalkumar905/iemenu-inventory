<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createVendorForm" class="form-horizontal" enctype="multipart/form-data" action="#">
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
			</div>
		</form>
	</div>
</div>