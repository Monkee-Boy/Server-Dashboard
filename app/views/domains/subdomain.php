<div class="row">
  <div class="large-8 columns">
    <h1>domains/subdomain (<?= $subdomain->subdomain ?>)</h1>
  </div>

  <div class="large-4 columns">
    <!-- Currently no buttons needed -->
  </div>
</div>

<ul class="breadcrumbs">
  <li><?= link_to('/', 'Home') ?></li>
  <li><?= link_to_route('domains', 'Domains') ?></li>
  <li><?= link_to_route('domain', $subdomain->domain, $subdomain->domain) ?></li>
  <li class="current"><?= $subdomain->subdomain ?></li>
</ul>

<div class="row">
  <div class="large-12 columns">
    <div class="chartStock" width="100%" data-chart-url="<?= route('chart_bandwidth_domain', array('domain_name'=>$subdomain->domain, 'subdomain_name'=>$subdomain->subdomain)) ?>" data-chart-title="Bandwidth" data-chart-series="Bandwidth"></div>
  </div>
</div>

<div class="row">
  <div class="large-12 columns">
    <div class="chartStock" width="100%" height="400px" data-chart-url="<?= route('chart_time_domain', array('domain_name'=>$subdomain->domain, 'subdomain_name'=>$subdomain->subdomain)) ?>" data-chart-title="Request Time" data-yaxis-title="Seconds"></div>
  </div>
</div>
