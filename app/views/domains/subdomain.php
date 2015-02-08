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

<ul class="tabs" data-tab data-options="deep_linking:true;scroll_to_content:false">
  <li class="tab-title active"><a href="#panel1">Over Time</a></li>
  <li class="tab-title"><a href="#panel2">Storage</a></li>
  <li class="tab-title"><a href="#panel3">Top</a></li>
  <li class="tab-title"><a href="#panel4">Query Builder</a></li>
</ul>

<hr>

<div class="tabs-content">
  <div class="content active" id="panel1">
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
  </div>

  <div class="content" id="panel2">
    <div class="row">
      <div class="large-6 columns">
        <div class="chartPie" data-chart-url="<?= route('chart_storage_total', ['domain_name'=>$subdomain->domain, 'subdomain_name'=>$subdomain->subdomain]); ?>" data-chart-title="Storage Breakdown" data-chart-type="storage" style="width: 100%; height: 400px;"></div>
      </div> <!-- /.large-6.columns -->

      <div class="large-6 columns">

      </div> <!-- /.large-6.columns -->
    </div> <!-- /.row -->
  </div> <!-- /.content -->

  <div class="content" id="panel3">
    <div class="row">
      <div class="large-6 columns">
        <h5>Top Referers <span data-tooltip aria-haspopup="true" class="has-tip" title="Top 10 referers in the last 30 days.">?</span></h5>
        <table class="chartTable" data-chart-url="<?= route('chart_top_referers', array('domain_name'=>$subdomain->domain, 'subdomain_name'=>$subdomain->subdomain)) ?>" style="width: 100%;">
          <thead>
            <tr>
              <th>Hits</th>
              <th>Referer</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div> <!-- /.large-6.columns -->

      <div class="large-6 columns">
        <h5>Top Pages <span data-tooltip aria-haspopup="true" class="has-tip" title="Top 10 pages requested in the last 30 days.">?</span></h5>
        <table class="chartTable" data-chart-url="<?= route('chart_top_pages', array('domain_name'=>$subdomain->domain, 'subdomain_name'=>$subdomain->subdomain)) ?>" style="width: 100%;">
          <thead>
            <tr>
              <th>Hits</th>
              <th>Page</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div> <!-- /.large-6.columns -->
    </div> <!-- /.row -->
  </div> <!-- /.content -->
  <div class="content" id="panel4">
    #TODO: Add query builder.
  </div>
</div>
