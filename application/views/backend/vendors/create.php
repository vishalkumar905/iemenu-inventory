<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<form method="post" id="createVendorForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
				<h4 class="card-title"><?=$headTitle?></h4>
			</div>
			<div class="card-content">
				<div class="row">
					<label class="col-md-2 label-on-left">Vendor Code*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="vendorCode" required="true" class="form-control" value="<?=!empty(set_value('vendorCode')) ? set_value('vendorCode') : (isset($vendorCode) ? $vendorCode : '') ?>">
							<?=form_error('vendorCode');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Vendor Name*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input type="text" name="vendorName"  required="true" class="form-control" value="<?=!empty(set_value('vendorName')) ? set_value('vendorName') : (isset($vendorName) ? $vendorName : '') ?>">
							<?=form_error('vendorName');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Vendor Email*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="email" name="vendorEmail" class="form-control" value="<?=!empty(set_value('vendorEmail')) ? set_value('vendorEmail') :  (isset($vendorEmail) ? $vendorEmail : '')?>">
							<?=form_error('vendorEmail');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Vendor Contact*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="text" name="vendorContact" class="form-control" value="<?=!empty(set_value('vendorContact')) ? set_value('vendorContact') :  (isset($vendorContact) ? $vendorContact : '')?>">
							<?=form_error('vendorContact');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">GST Number*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="text" name="gstNumber" class="form-control" value="<?=!empty(set_value('gstNumber')) ? set_value('gstNumber') :  (isset($gstNumber) ? $gstNumber : '')?>">
							<?=form_error('gstNumber');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">PAN Number*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="text" name="panNumber" class="form-control" value="<?=!empty(set_value('panNumber')) ? set_value('panNumber') :  (isset($panNumber) ? $panNumber : '')?>">
							<?=form_error('panNumber');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Service Tax Number</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<label class="control-label"></label>
							<input required="true" type="text" name="serviceTaxNumber" class="form-control" value="<?=!empty(set_value('serviceTaxNumber')) ? set_value('serviceTaxNumber') :  (isset($serviceTaxNumber) ? $serviceTaxNumber : '')?>">
							<?=form_error('serviceTaxNumber');?>
						</div>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 label-on-left">Contract Date From - To*</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group label-floating">
									<label class="control-label"></label>
									<input required="true" type="date" name="contractDateFrom" class="form-control" value="<?=!empty(set_value('contractDateFrom')) ? set_value('contractDateFrom') :  (isset($contractDateFrom) ? $contractDateFrom : '')?>">
									<?=form_error('contractDateFrom');?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group label-floating">
									<label class="control-label"></label>
									<input required="true" type="date" name="contractDateTo" class="form-control" value="<?=!empty(set_value('contractDateTo')) ? set_value('contractDateTo') :  (isset($contractDateTo) ? $contractDateTo : '')?>">
									<?=form_error('contractDateTo');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
						<div class="checkbox">
							<label>
								<?=form_hidden('useTax', 0)?>
								<input type="checkbox" name="useTax" value="1" <?=!empty(set_value('useTax')) ? "checked" : (isset($useTax) && $useTax == 1 ? 'checked' : '')?>>Use tax
							</label>
						</div>
					</div>
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