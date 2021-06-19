<div class="col-md-12">
	<div class="card mt-0">
		<div class="card-content">
			<div class="pl-0 card-header card-header-text">
				<h4 class="card-title">Manage Recipes</h4>
				<input type="button" name="exportData" id="exportData" value="CSV" class="btn btn-info btn-sm">
				<input type="button" name="exportData" id="exportData" value="EXCEL" class="btn btn-info btn-sm">
				<button type="button" name="exportData" id="exportData" value="MENU" class="btn btn-info btn-sm">MENU EXCEL</button>
			</div>
			<div class="toolbar mb-10">

			</div>
			<div class="table-responsive">
				<table id="recipeManagementData" class="table table-bordered" cellspacing="0" width="100%" style="width:100%">
					<thead class="text-primary">
						<tr>
							<th>S.No</th>
							<th>Item Name</th>
							<th>Recipe</th>
							<th>isConfigured</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="viewItemRecipeModal" tabindex="-1" role="dialog" aria-labelledby="viewItemRecipeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="material-icons">clear</i>
				</button>
				<h4 class="modal-title">Recipe Details</h4>
			</div>
			<div class="modal-body" id="itemRecipeDetails">
	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>