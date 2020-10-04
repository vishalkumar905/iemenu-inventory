<ul class="nav">
	<li>
		<a href="<?=base_url()?>backend/dashboard">
			<i class="material-icons">dashboard</i>
			<p>Dashboard</p>
		</a>
	</li>
	<li>
		<a data-toggle="collapse" href="#products">
			<i class="material-icons">menu</i>
			<p>Products
				<b class="caret"></b>
			</p>
		</a>
		<div class="collapse" id="products">
			<ul class="nav">
				<li>
					<a href="<?=base_url()?>tax/create">Create Products</a>
				</li>
				<li>
					<a href="<?=base_url()?>backend/products/manage">Manage Products</a>
				</li>
			</ul>
		</div>
	</li>
	<li>
		<a data-toggle="collapse" href="#tax">
			<i class="material-icons">style</i>
			<p>Tax
				<b class="caret"></b>
			</p>
		</a>
		<div class="collapse" id="tax">
			<ul class="nav">
				<li>
					<a href="<?=base_url()?>tax/create">Create Tax</a>
				</li>
				<li>
					<a href="<?=base_url()?>tax/manage">Manage Taxes</a>
				</li>
			</ul>
		</div>
	</li>
</ul>