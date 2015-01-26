<div class="row">
	<div class="large-8 columns">
		<h1>Welcome</h1>
	</div>

	<div class="large-4 columns">
		<!-- Currently no buttons needed -->
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
		<div class="chartProgress" data-chart-url="<?= route('linode_progress') ?>" data-chart-title="Billing Cycle"></div>
	</div>
</div>

<div class="row">
	<div class="large-6 columns">
		<div class="chartGuage" data-chart-url="<?= route('linode_bandwidth') ?>" data-chart-title="Bandwidth Used" style="width:100%;height:245px;"></div>
	</div>
	<div class="large-6 columns">
		<div class="chartGuage" data-chart-url="<?= route('linode_storage') ?>" data-chart-title="Storage Used" style="width:100%;height:245px;"></div>
	</div>
</div>
