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
        <td>
          When <?php if($notification->how === 'reaches') { echo 'server'; } else { echo 'domain'; } ?>
          <code><?= $notification->what ?></code>
          <code><?= $notification->how ?></code>
          <?php if($notification->how !== 'reaches') { echo ' by '; } ?>
          <code><?= $notification->by.$notification->by_measure ?></code>, notify
          <?php if(!empty($notification->where)) { echo '<code>'.$notification->where.'</code>'; } ?>
          <?php if(!empty($notification->where) && $notification->hipchat === 1) { echo ' and '; } ?>
          <?php if($notification->hipchat === 1) { echo '<code>Hipchat</code>'; } ?>
        </td>
        <td>
          <?= Form::open(array('route' => array('notifications.destroy', $notification->id), 'method' => 'DELETE')); ?>
            <ul class="button-group radius">
              <li><?= link_to_route('notifications.edit', 'Edit', $notification->id, array('class'=>'button tiny')); ?></li>
              <li><button type="submit" class="alert tiny">Remove</button></li>
            </ul>
          <?= Form::close(); ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
