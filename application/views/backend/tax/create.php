<div class="col-md-12" id="taxPageContainer">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createTaxForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>

			<div class="card-content">
				<div class="row">
					<label class="col-md-2 label-on-left">Tax Name*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="taxName"  class="form-control" value="<?=!empty(set_value('taxName')) ? set_value('taxName') : (isset($taxName) ? $taxName : '') ?>">
							<?=form_error('taxName');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Tax Percentage*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="taxPercentage"  class="form-control" value="<?=!empty(set_value('taxPercentage')) ? set_value('taxPercentage') : (isset($taxPercentage) ? $taxPercentage : '') ?>">
							<?=form_error('taxPercentage');?>
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