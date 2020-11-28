<div class="row" id="manageRequestTransferPageContainer">
    <div class="col-md-12" id="manageRequestTransferContainer">



        <div class="card mt-0">
			<div class="card-header">
				<h4 class="card-title">Manage <?= $this->navTitle?></h4>
			</div>
            <div class="card-content">
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

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">Incomming</a></li>
					<li><a data-toggle="tab" href="#menu1">Outgoing</a></li>
				</ul>
				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<div class="table-responsive">
							<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
								<thead class="text-primary">
									<tr>
										<th>IR NO</th>
										<th>Transfer From</th>
										<th>Transfer To</th>
										<th>Status</th>
										<th>Date</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="manageIncommingRequestTransferTableBody"></tbody>
							</table>
						</div>
						
						<div id="pagination">
								
						</div>
					</div>
					<div id="menu1" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
								<thead class="text-primary">
									<tr>
										<th>IR NO</th>
										<th>Transfer From</th>
										<th>Transfer To</th>
										<th>Status</th>
										<th>Date</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="manageOutgoingRequestTransferTableBody"></tbody>
							</table>
						</div>
						
						<div id="pagination">
								
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>