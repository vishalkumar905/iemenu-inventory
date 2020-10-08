<div class="col-md-12">
	<?=showAlertMessage($flashMessage, $flashMessageType)?>
	<div class="card mt-0">
		<div class="card-content">

			<div class="pl-0 card-header card-header-text">
				<h4 class="card-title">Manage Products</h4>
				<input type="button" name="exportData" id="exportData" value="CSV" class="btn btn-info btn-sm">
				<input type="button" name="exportData" id="exportData" value="EXCEL" class="btn btn-info btn-sm">
			</div>
			<div class="toolbar mb-10">

			</div>
			<div class="table-responsive">
				<table id="productsData" class="table table-bordered" cellspacing="0" width="100%" style="width:100%">
					<thead class="text-primary">
						<tr>
							<th>S.No</th>
							<th>Product Code</th>
							<th>Product Name</th>
							<th>Product Type</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>