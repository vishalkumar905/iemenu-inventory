<div class="" id="manageRequestTransferReportPageContainer">
	<div class="col-md-12">
		<div class="card mt-0">
			<div class="card-header">
				<h4 class="card-title">Manage <?= $this->navTitle?></h4>
			</div>
			<div class="card-content">
				<div class="toolbar mb-10">
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<input type="button" name="exportData" id="exportData" value="CSV" class="btn btn-info btn-sm">
							<input type="button" name="exportData" id="exportData" value="EXCEL" class="btn btn-info btn-sm">
							<select class='mt-10 btn-sm pt-4 pb-4' id="tableDataLimit">
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="-1">All</option>
							</select>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered custom-table now-wrap" cellspacing="0" width="100%" style="width:100%">
						<thead class="text-primary">
							<tr>
								<th>S.No</th>
								<th>Request No</th>
								<th>Request from</th>
								<th>Request to</th>
								<th>Request type</th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Unit</th>
								<th>Requested Qty</th>
								<th>Dispatched Qty</th>
								<th>Received Qty</th>
								<th>Dispute Qty</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody id="manageRequestTransferTableBody"></tbody>
					</table>
				</div>
				
				<div id="pagination">
						
				</div>
			</div>
		</div>
	</div>
</div>