<div class="row" id="manageDisputeRequestPageContainer">
    <div class="col-md-12">
        <div class="card mt-0">
			<div class="card-header">
				<h4 class="card-title"><?= $this->navTitle?> <span id="actionPerformedBy"></span> </h4>
			</div>
            <div class="card-content">
				<div class="table-responsive">
					<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
						<thead class="text-primary">
							<tr>
								<th></th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Unit</th>
								<th>Requested Qty</th>
								<th>Dispatched Qty</th>
								<th>Received Qty</th>
								<th>Dispute Qty</th>
								<th>Dispatcher Msg</th>
								<th>Receiver Msg</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="manageDisputeRequestTableBody"></tbody>
					</table>
				</div>
				
				<div id="pagination"></div>
            </div>
        </div>
    </div>
</div>