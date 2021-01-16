<div class="col-md-12" id="manageDirectOrderReportContainer">
	<div class="card mt-0">
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
						</select>
					</div>
					<div class="col-md-8 col-xs-12">
						<div class="form-group mt-0 is-empty">
							<input type="text" name="searchBar" id="searchBar" class="form-control" placeholder="Product Name">
						</div>
					</div>
				</div>
			</div>

			<form id="directOrderReportForm" method="POST">
				<div class="table-responsive">
					<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
						<thead class="text-primary">
							<tr class="th-nowwrap">
								<th>S.N0</th>
								<th>Vendor Name</th>
								<th>Bill Number</th>
								<th>Bill Date</th>
								<th>OS</th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Unit</th>
								<th width="10%">Qty</th>
								<th>Unit Price</th>
								<th>Comment</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody id="directOrderReportTableBody">
						</tbody>
					</table>
				</div>
			</form>
			
			<div id="pagination">
					
			</div>
		</div>
	</div>
</div>