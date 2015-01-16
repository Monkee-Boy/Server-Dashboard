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
    #TODO: Add subdomain overview graphs
  </div>
</div>
