<div class="col-md-12 displaynone" id="manageClosingStockContainer">
	<div class="card mt-0">
		<div class="card-content">

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
					<div class="col-md-6">
						<input type="text" name="searchBar" id="searchBar" class="form-control" placeholder="Product Name">
					</div>
				</div>
			</div>

			<form id="closingStockForm" method="POST">
				<div class="table-responsive">
					<table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
						<thead class="text-primary">
							<tr>
								<th></th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Unit</th>
								<th width="10%">Qty</th>
								<?php /*?>
								<th>Unit Price</th>
								<th  width="10%">Sub Total</th>
								<?php*/ ?>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody id="closingStockTableBody"></tbody>
					</table>
				</div>
			</form>
			
			<div id="pagination">
					
			</div>

			<div class="toolbar mb-10">
				<div class="row">
					<div class="col-md-12">
						<button type="button" name="submit" value="Save" id="saveClosingStock" class="btn btn-rose btn-fill">Submit<div class="ripple-container"></div></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>