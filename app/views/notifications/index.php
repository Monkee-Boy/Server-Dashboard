<div class="row">
  <div class="large-8 columns">
    <h1>Notifications</h1>
  </div>

  <div class="large-4 columns">
    <?= link_to_route('notifications.create', 'Create Notification', array(), array('class' => 'button small right')); ?>
  </div>
</div>

<ul class="breadcrumbs">
  <li><?= link_to('/', 'Home') ?></li>
  <li class="current">Notifications</li>
</ul>

<table style="width: 100%;">
  <thead>
    <tr>
      <th>Notification</th>
      <th>Actions</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach($notifications as $notification) { ?>
      <tr>
        <td>When <?php if($notification->how === 'reach') { echo 'server'; } else { echo 'domain'; } ?> <code><?= $notification->what ?></code> <code><?= $notification->how ?></code> by <code><?= $notification->by.$notification->by_measure ?></code> notify <code><?= $notification->where ?></code></td>
        <td>
          <?= link_to_route('notifications.edit', 'Edit &bull;', $notification->id); ?>
          <?= link_to_route('notifications.destroy', 'Remove', $notification->id); ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
