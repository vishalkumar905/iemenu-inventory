<ul class="nav main-menu">
	<li>
		<a href="<?=base_url()?>backend/dashboard">
			<i class="material-icons">dashboard</i>
			<p>Dashboard</p>
		</a>
	</li>
	<li>
		<a href="<?=base_url()?>backend/products">
			<i class="material-icons">menu</i>
			<p>Products</p>
		</a>
	</li>
	<?php /* ?>
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
					<a href="<?=base_url()?>backend/products/create">Create Product</a>
				</li>
				<li>
					<a href="<?=base_url()?>backend/products/manage">Manage Products</a>
				</li>
			</ul>
		</div>
	</li>
	<?php */ ?>

	<li>
		<a data-toggle="collapse" href="#tax">
			<i class="material-icons">style</i>
			<p>Tax<b class="caret"></b></p>
		</a>
		<div class="collapse" id="tax">
			<ul class="nav">
				<li>
					<a href="<?=base_url()?>backend/tax">Create Tax</a>
				</li>
				<li>
					<a href="<?=base_url()?>backend/producttax">Map Product Taxes</a>
				</li>
			</ul>
		</div>
	</li>
	<li>
		<a data-toggle="collapse" href="#vendorSideBar">
			<i class="material-icons">style</i>
			<p>Vendors<b class="caret"></b></p>
		</a>
		<div class="collapse" id="vendorSideBar">
			<ul class="nav">
				<li>
					<a href="<?=base_url()?>backend/vendors">Manage Vendors</a>
				</li>
				<li>
					<a href="<?=base_url()?>backend/vendorproducts">Vendor Products</a>
				</li>
			</ul>
		</div>
	</li>
</ul>