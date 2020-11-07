<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createOpeningStockForm" class="form-horizontal" enctype="multipart/form-data" action="#">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>
			<div class="card-content">
				<div class="row col-md-12">
					<span><b>Opening Stock No: </b><?=OPENING_STOCK_SHORT_NAME?>-<?=$openingStockNumber?><span>
					<span class="pull-right"><b>Date: </b><?=Date('d-m-Y')?></span>
				</div>

				<div class="row">
					<label class="col-md-2 label-on-left">Product Type</label>
					<div class="col-sm-10">
						<?php
							$extra = [
								'id' => 'productType',
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
					<label class="col-md-2 label-on-left">Categories</label>
					<div class="col-md-10">
						<?php
							
							if (!empty($dropdownSubCategories))
							{
								$ddOptions = '';
								$ddCounter = 0;
								foreach($dropdownSubCategories as $subCategoryIndex => $subCategoryName)
								{
									if ($ddCounter == 0)
									{
										$ddOptions .= sprintf('<option disabled value>%s</option>', $subCategoryName);
										$ddOptions .= sprintf('<option value="0">%s</option>', 'All Category');
									}
									else
									{
										$ddOptions .= sprintf('<option value="%s">%s</option>', $subCategoryIndex, $subCategoryName);
									}

									$ddCounter++;
								}

								if ($ddOptions)
								{
									echo sprintf("<select id='category' name='category[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>", $ddOptions);
								}
							}
						?>
					</div>
				</div>
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
			</div>
		</form>
	</div>
</div>