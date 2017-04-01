<header id="navbar" role="banner" class="<?php print $navbar_classes; ?>">
  <div class="<?php print $container_class; ?>">
    <div class="navbar-header">
      <?php if ($logo): ?>
        <a class="logo flip navbar-btn pull-left" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
      <?php endif; ?>

      <?php if (!empty($primary_nav) || !empty($secondary_nav) || !empty($page['navigation'])): ?>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
          <span class="sr-only"><?php print t('Toggle navigation'); ?></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      <?php endif; ?>
    </div>

    <?php if (!empty($primary_nav) || !empty($secondary_nav) || !empty($page['navigation'])): ?>
      <div class="navbar-collapse collapse" id="navbar-collapse">
        <nav role="navigation">
          <?php if (!empty($primary_nav)): ?>
            <?php print render($primary_nav); ?>
          <?php endif; ?>
          <?php if (!empty($page['navigation'])): ?>
            <?php print render($page['navigation']); ?>
          <?php endif; ?>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</header>
<div class="row ticker-wrapper">
  <div class="col-sm-2">
    <h3>جديد الموقع</h3>
  </div>
  <div class="col-sm-8">
    <?php print $recent_content_ticker; ?>
  </div>
  <div class="col-sm-2">
    <div class="social-icons">
      <ul class="list-inline ">
        <li><a href="http://bit.ly/1dTnfr9" title="تطبيق الاندرويد " target="_blank"><i class="fa fa-android"></i></a></li>
        <li><a href="http://bit.ly/miraathios" title="تطبيق ابل"><i class="fa fa-apple"></i></a></li>
        <li><a href="https://twitter.com/miraathnet" title="Twitter"><i class="fa fa-twitter-square"></i></a></li>
        <li><a href="https://www.facebook.com/admin.ar.miraath" title="Facebook"><i class="fa fa-facebook-square"></i></a></li>
        <li><a href="https://www.youtube.com/user/MiraathNet" title="Youtube"><i class="fa fa-youtube-square"></i></a></li>
      </ul>
    </div>
  </div>
</div>
<?php
    if(drupal_is_front_page()){
      require(drupal_get_path('theme', 'mir').'/templates/system/front-content.tpl.php');
    }
?>
<div class="main-container <?php print $container_class; ?>">

  <div class="row">

    <?php if (!empty($page['sidebar_first'])): ?>
      <aside class="col-sm-3" role="complementary">
        <?php print render($page['sidebar_first']); ?>
      </aside>  <!-- /#sidebar-first -->
    <?php endif; ?>

    <section<?php print $content_column_class; ?>>
      <?php if (!empty($page['highlighted'])): ?>
        <div class="highlighted jumbotron"><?php print render($page['highlighted']); ?></div>
      <?php endif; ?>
      <?php if (!empty($breadcrumb)): print $breadcrumb; endif;?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if (!empty($title)): ?>
        <h1 class="page-header"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php if (!empty($tabs)): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>
      <?php if (!empty($page['help'])): ?>
        <?php print render($page['help']); ?>
      <?php endif; ?>
      <?php if (!empty($action_links)): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
    </section>

    <?php if (!empty($page['sidebar_second'])): ?>
      <aside class="col-sm-3" role="complementary">
        <?php print render($page['sidebar_second']); ?>
      </aside>  <!-- /#sidebar-second -->
    <?php endif; ?>

  </div>
</div>

<!-- <?php if (!empty($page['footer'])): ?>
  <footer class="footer <?php print $container_class; ?>">
    <?php print render($page['footer']); ?>
  </footer>
<?php endif; ?> -->
<div class="sticky-bottom">
  <div id="audio-player-wrapper" class="audio-player-wrapper">
    <div class="toggle-show"><i class="toggle-show-icon fa fa-plus"></i></div>
    <audio id="audio-player" controls>
      Your browser does not support the audio element.
    </audio>
  </div>
</div>

<footer>
  <div class="col-sm-offset-1 col-sm-9">© موقع ميراث الأنبياء 2016 جميع الحقوق محفوظة</div>
  <div class="col-sm-2"></div>
</footer>
