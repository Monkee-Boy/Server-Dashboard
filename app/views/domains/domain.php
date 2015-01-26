<div class="row">
  <div class="large-8 columns">
    <h1><?= $domain_name ?></h1>
  </div>

  <div class="large-4 columns">
    <!-- Currently no buttons needed -->
  </div>
</div>

<ul class="breadcrumbs">
  <li><?= link_to('/', 'Home') ?></li>
  <li><?= link_to_route('domains', 'Domains') ?></li>
  <li class="current"><?= $domain_name ?></li>
</ul>

<div class="row">
  <div class="large-12 columns">
    <div class="chartStock" width="100%" height="400px" data-chart-url="<?= route('chart_bandwidth_domain', array('domain_name'=>$domain_name)) ?>" data-chart-title="Bandwidth" data-yaxis-title="Kilobytes"></div>
  </div>
</div>

<div class="row">
  <div class="large-12 columns">
    <div class="chartStock" width="100%" height="400px" data-chart-url="<?= route('chart_time_domain', array('domain_name'=>$domain_name)) ?>" data-chart-title="Request Time" data-yaxis-title="Seconds"></div>
  </div>
</div>

<table style="width: 100%;">
  <thead>
    <tr>
      <th>Subdomain</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach($subdomains as $subdomain) { ?>
      <tr>
        <td><?= link_to_route('subdomain', $subdomain->subdomain, array('domain_name' =>$subdomain->domain, 'subdomain_name' => $subdomain->subdomain)); ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
