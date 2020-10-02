<ul class="nav">
	<li class="active">
		<a href="dashboard.html">
			<i class="material-icons">dashboard</i>
			<p>Dashboard</p>
		</a>
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