<div class="col-md-12">
	<div class="card mt-0">
		<div class="card-content">
			<div class="pl-0 card-header card-header-text">
				<h4 class="card-title">Manage Taxes</h4>
			</div>
			<div class="toolbar mb-10">

			</div>
			<div class="table-responsive">
				<table id="productsData" class="table table-bordered dataTable" cellspacing="0" width="100%" style="width:100%">
					<thead class="text-primary">
						<tr>
							<th>S.No</th>
							<th>Tax Name</th>
							<th>Taxt Percentage</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php 
					if(!empty($taxData))
					{
						$counter = 0; 
						foreach($taxData as $taxRow)
						{
							$taxEditUrl = sprintf('
								<span class="text-right">
									<a href="%s%s" class="btn btn-simple btn-info btn-icon"><i class="material-icons">edit</i></a>
								</span>
							', $this->editPageUrl, $taxRow['id']);
					?>
						<tr>
							<td><?=++$counter?></td>
							<td><?=$taxRow['taxName']?></td>
							<td><?=$taxRow['taxPercentage']?></td>
							<td><?=$taxEditUrl?></td>
						</tr>
					<?php }
					} 
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>