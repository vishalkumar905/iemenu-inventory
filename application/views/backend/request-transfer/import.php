<div class="col-md-12">
	<div class="card mt-0">
		<form method="post" id="importExcelForm" class="form-horizontal" enctype="multipart/form-data" action="<?=current_url()?>">
			<div class="card-header card-header-text">
                <h4 class="card-title">Upload Product Excel File</h4>
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
						<a href="<?=base_url() . 'backend/products/downloadSample'?>" id="#downloadProductSample"><b>Download Sample Excel</b></a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<div class="row" id="manageRequestTransferPageContainer">
    <div class="col-md-12" id="manageRequestTransferContainer">
		<div class="card">
			<div class="card-content">
				<ul class="nav nav-pills nav-pills-warning">
					<li class="active">
						<a href="panels.html#pill1" data-toggle="tab">Direct Transfer</a>
					</li>
					<li>
						<a href="panels.html#pill2" data-toggle="tab">Replenishment Request</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="pill1">
						<?php /*?>
						<div class="toolbar mb-10">
							<div class="row">
								<div class="col-md-6">
									<select class='mt-10' id="tableDataLimit">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select>
								</div> 
								<!-- <div class="col-md-6">
									<input type="text" name="searchBar" id="searchBar" class="form-control" placeholder="Product Name">
								</div> -->
							</div>
						</div>
						<?php */?>

						<div class="table-responsive">
							<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
								<thead class="text-primary">
									<tr>
										<th>IR NO</th>
										<th>Transfer From</th>
										<th>Transfer To</th>
										<th>Date</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="requestTransferTableBody"></tbody>
							</table>
						</div>
						
						<div id="pagination"></div>
					</div>
					<div class="tab-pane" id="pill2">
						
					</div>
				</div>
			</div>
		</div>
    </div>
</div>