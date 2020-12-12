<div class="col-md-12" id="manageReportContainer">
	<div class="card mt-0">
		<div class="card-content">

			<div class="toolbar mb-10">
				<div class="row">
					<div class="col-xs-6">
						<select class='mt-10' id="tableDataLimit">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>
					<div class="col-xs-6">
						<div class="form-group mt-0 is-empty">
							<input type="text" name="searchBar" id="searchBar" class="form-control" placeholder="Product Name">
						</div>
					</div>

					<div class="col-xs-12">
						<input type="button" name="exportData" id="exportData" value="CSV" class="btn btn-info btn-sm">
						<input type="button" name="exportData" id="exportData" value="EXCEL" class="btn btn-info btn-sm">
					</div>
				</div>
			</div>

			<form id="reportForm" method="POST">
				<div class="table-responsive">
					<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
						<thead class="text-primary">
							<tr>
								<th rowspan="2">Date</th>
								<th rowspan="2">Product Code</th>
								<th rowspan="2">Product Name</th>
								<th colspan="2">Avg Price</th>
								<th colspan="2">Opening Inventory</th>
								<th colspan="2">Purchase Inventory</th>
								<th colspan="2">Wastage Inventory</th>
								<th colspan="2">Transfer Stocks</th>
								<th colspan="2">Current Inventory</th>
								<th colspan="3">Closing Inventory</th>
								<th colspan="2">Consumption</th>
							</tr>
							<tr>
								<th>Unit</th>
								<th>Price</th>
								<th>Qty</th>
								<th>Amt</th>
								<th>Qty</th>
								<th>Amt</th>
								<th>Qty</th>
								<th>Amt</th>
								<th style="white-space: nowrap;">In Qty</th>
								<th style="white-space: nowrap;">Out Qty</th>
								<th>Qty</th>
								<th>Amt</th>
								<th>Qty</th>
								<th>Amt</th>
								<th>Date</th>
								<th>Qty</th>
								<th>Amt</th>
							</tr>
						</thead>
						<tbody id="reportTableBody">
						</tbody>
					</table>
				</div>
			</form>
			
			<div id="pagination">
					
			</div>
		</div>
	</div>
</div>