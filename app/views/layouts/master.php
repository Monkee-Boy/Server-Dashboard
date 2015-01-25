<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>mBoy Server Dashboard</title>

  <link rel="stylesheet" href="/css/style.min.css?v=1">
  <script src="/bower_components/modernizr/modernizr.js"></script>
</head>
<body>
  <nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
      <li class="name">
        <h1><?= link_to('/', 'Dashboard') ?></h1>
      </li>

      <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
    </ul>

    <section class="top-bar-section">
      <?php if(Auth::check()) { ?>
        <!-- Right Nav Section -->
        <ul class="right">
          <li class="has-form"><?= link_to_route('domains', 'Domains', array(), array('class'=>'button')) ?></li>
          <li class="has-form"><?= link_to_route('notifications.index', 'Notifications', array(), array('class'=>'button')) ?></li>
          <li class="divider"></li>
          <li><?= link_to_route('user/logout', 'Logout') ?></li>
        </ul>
      <?php } ?>
    </section>
  </nav>

  <?php if(Session::has('message')) { ?>
    <div class="row">
      <div class="large-12 columns">
        <div data-alert class="alert-box">
          <?= Session::get('message'); ?>
          <a href="#" class="close">&times;</a>
        </div>
      </div>
    </div>
  <?php } ?>

  <div class="row">
    <div class="large-12 columns">
      <?= $content; ?>
    </div> <!-- /.large-12.columns -->
  </div> <!-- /.row -->

  <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
  <script>window.jQuery || document.write('<script src="/js/jquery.min.js"><\/script>')</script>
  <script src="/bower_components/foundation/js/foundation.min.js"></script>
  <script src="/bower_components/highstock/highstock.js"></script>
  <script src="/bower_components/highstock/modules/exporting.js"></script>
  <script src="/js/app.js?v=1"></script>
</body>
</html>
