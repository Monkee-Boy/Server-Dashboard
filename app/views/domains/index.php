<div class="row">
  <div class="large-8 columns">
    <h1>Domains</h1>
  </div>

  <div class="large-4 columns">
    <!-- Currently no buttons needed -->
  </div>
</div>

<ul class="breadcrumbs">
  <li><?= link_to('/', 'Home') ?></li>
  <li class="current">Domains</li>
</ul>

<table style="width: 100%;">
  <thead>
    <tr>
      <th>Domain</th>
      <th>Subdomains</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach($domains as $domain) { ?>
      <tr>
        <td><?= link_to_route('domain', $domain->domain, $domain->domain); ?></td>
        <td><?= $domain->subdomains ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
