<div class="row" id="viewRequestsPageContainer">
    <div class="col-md-12">
		<?=showAlertMessage($flashMessage, $flashMessageType)?>
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
								<th>Requested Qty</th>
								<th>Dispatched Qty</th>
								<th>Received Qty</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody id="viewRequestsTableBody"></tbody>
					</table>
				</div>
				
				<div id="pagination"></div>

				<?php if ($showDisptachBtn || $showReceiveBtn) { ?>
					<div class="toolbar mb-10">
						<div class="row">
							<div class="col-md-12">
								<?php if ($showDisptachBtn) {?>
									<button type="button" name="Dispatch" value="<?=STATUS_DISPATCHED?>" class="btn btn-warning btn-fill acceptRequest">Dispatch<div class="ripple-container"></div></button>
								<?php } ?>
								
								<?php if ($showReceiveBtn) {?>
									<button type="button" name="Received" value="<?=STATUS_RECEIVED?>" class="btn btn-success btn-fill acceptRequest">Received<div class="ripple-container"></div></button>
								<?php } ?>

								<?php if ($showDisptachBtn || $showRejectBtn) {?>
									<button type="button" name="Reject" value="<?=STATUS_REJECTED?>" class="btn btn-danger btn-fill acceptRequest">Reject<div class="ripple-container"></div></button>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>