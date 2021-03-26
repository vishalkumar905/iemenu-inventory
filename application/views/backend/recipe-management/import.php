<div class="col-md-12">
	<div class="card mt-0">
		<form method="post" id="importExcelForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
                <h4 class="card-title">Upload Recipes Excel File</h4>
			</div>
			<div class="card-content">
				<div class="row">
					<label class="col-md-2 label-on-left">Excel File*</label>
					<div class="col-sm-10">
						<div class="form-group label-floating">
							<input type="file" class="default" name="excelFile" id="excelFile" />
							<p id="excelFileErrorMsg" class="text-danger"></p>
						</div>
					</div>
				</div>
				<div class="row mt-10">
					<label class="col-sm-2 label-on-left"></label>
					<div class="col-sm-10">
						<button type="button" name="uploadExcel" id="uploadExcel" value="Upload"  class="btn btn-rose btn-fill">Upload<div class="ripple-container"></div></button>
						<a href="<?=base_url() . 'backend/recipemanagement/downloadSample'?>" id="#downloadRecipeSample"><b>Download Sample Excel</b></a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>