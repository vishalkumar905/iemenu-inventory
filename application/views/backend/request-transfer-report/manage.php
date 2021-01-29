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
							</select>
						</div>
					</div>
				</div>

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">Incomming</a></li>
					<li><a data-toggle="tab" href="#menu1">Outgoing</a></li>
					<li><a data-toggle="tab" href="#menu2">Dispute</a></li>
				</ul>
				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<div class="table-responsive">
							<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
								<thead class="text-primary">
									<tr>
										<th>S.No</th>
										<th>Request from</th>
										<th>Request to</th>
										<th>Request type</th>
										<th>Status</th>
										<th>Date</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="manageIncommingRequestTransferTableBody"></tbody>
							</table>
						</div>
						
						<div id="incommingPagination">
								
						</div>
					</div>
					<div id="menu1" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
								<thead class="text-primary">
									<tr>
										<th>S.No</th>
										<th>Request from</th>
										<th>Request to</th>
										<th>Request type</th>
										<th>Status</th>
										<th>Date</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="manageOutgoingRequestTransferTableBody"></tbody>
							</table>
						</div>
						
						<div id="outgoingPagination">
								
						</div>
					</div>
					<div id="menu2" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
								<thead class="text-primary">
									<tr>
										<th>S.No</th>
										<th>Request from</th>
										<th>Request to</th>
										<th>Request type</th>
										<th>Date</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="manageDisputeTableBody"></tbody>
							</table>
						</div>
						
						<div id="disputePagination">
								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>