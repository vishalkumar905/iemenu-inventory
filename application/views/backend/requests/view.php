<div class="row" id="viewRequestsPageContainer">
    <div class="col-md-12" id="viewRequestsContainer">
        <div class="card mt-0">
			<div class="card-header">
				<h4 class="card-title"><?= $this->navTitle?></h4>
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
								<th>Qty</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody id="viewRequestsTableBody"></tbody>
					</table>
				</div>
				
				<div id="pagination"></div>

				<?php if ($isSender) { ?>
					<div class="toolbar mb-10">
						<div class="row">
							<div class="col-md-12">
								<button type="button" name="Accept" value="Accept" id="acceptRequest" class="btn btn-success btn-fill">Accept<div class="ripple-container"></div></button>
								<button type="button" name="Save" value="Save" id="rejectRequest" class="btn btn-danger btn-fill">Reject<div class="ripple-container"></div></button>
							</div>
						</div>
					</div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>