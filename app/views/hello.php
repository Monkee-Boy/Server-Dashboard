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

<div class="row">
	<div class="large-6 columns">
		<h5>Bandwidth Breakdown <span data-tooltip aria-haspopup="true" class="has-tip" title="Total bandwidth for the last 3 months.">?</span></h5>
		<table class="chartTable" data-chart-url="<?= route('chart_month_breakdown') ?>" style="width: 100%;">
			<thead>
				<tr>
					<th>Month</th>
					<th>Bandwidth</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<div class="large-6 columns">
		
	</div>
</div>
