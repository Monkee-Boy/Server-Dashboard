<div class="row">
  <div class="large-8 columns">
    <h1>Create Notification</h1>
  </div>

  <div class="large-4 columns">

  </div>
</div>

<ul class="breadcrumbs">
  <li><?= link_to('/', 'Home') ?></li>
  <li><?= link_to_route('notifications.index', 'Notifications') ?></li>
  <li class="current">Create</li>
</ul>

<?php if($errors->all()) { ?>
  <div class="row">
    <div class="large-12 columns">
      <?= HTML::ul($errors->all()); ?>
    </div>
  </div>
<?php } ?>

<?= Form::open(array('route' => array('notifications.store'))); ?>
  <table style="width: 100%;">
    <thead>
      <tr>
        <th>What</th>
        <th>How</th>
        <th>By</th>
        <th>Notify</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td><?= Form::select('what', array('bandwidth'=>'bandwidth','storage'=>'storage')) ?></td>
        <td><?= Form::select('how', array('increase'=>'increase','decrease'=>'decrease','reach'=>'reach')) ?></td>
        <td>
          <div class="row">
            <div class="large-8 columns">
              <?= Form::text('by') ?>
            </div>
            <div class="large-4 columns">
              <?= Form::select('by_measure', array('%'=>'%','MB'=>'MB','GB'=>'GB','TB'=>'TB')) ?>
            </div>
          </div>
        </td>
        <td><?= Form::text('where') ?></td>
      </tr>
    </tbody>
  </table>
  <?= Form::submit('Create Notification', array('class' => 'button tiny')) ?>
<?= Form::close(); ?>
