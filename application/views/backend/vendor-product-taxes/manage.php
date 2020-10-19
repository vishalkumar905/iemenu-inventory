<div class="col-md-12 displaynone" id="manageVendorProductTaxContainer">
	<div class="card mt-0">
		<div class="card-content">
			<div class="pl-0 card-header card-header-text  pt-0">
				<h4 class="card-title">Manage Product Taxes</h4>
				<?php /*?>
				<input type="button" name="exportData" id="exportData" value="CSV" class="btn btn-info btn-sm">
				<input type="button" name="exportData" id="exportData" value="EXCEL" class="btn btn-info btn-sm">
				<?php */?>
			</div>
			<div class="toolbar mb-10">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<input type="text" name="searchBar" id="searchBar" class="form-control" placeholder="Product Name">
					</div>
				</div>
			</div>

			<form id="vendorProductTaxForm" method="POST">
				<div class="table-responsive">
					<table id="vendorProductWithTaxesData" class="table table-bordered custom-table displaynone" cellspacing="0" width="100%" style="width:100%">
						<thead id="vendorProductTableHeading" class="text-primary"></thead>
						<tbody id="vendorProductTableBody"></tbody>
					</table>
				</div>
			</form>
			
			<div id="pagination">
					
			</div>

			<div class="toolbar mb-10">
				<div class="row">
					<div class="col-md-12">
						<button type="button" name="submit" value="Save" id="saveVendorProductTax" class="btn btn-rose btn-fill">Map Product<div class="ripple-container"></div></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>