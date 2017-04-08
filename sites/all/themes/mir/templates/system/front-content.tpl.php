<div class="container">
  <div class="carousel hidden-xs">
    <?php print $front_carousel; ?>
  </div>
  <div class="row">
    <div class="col-sm-8">
      <div class="sub-title-wrapper">
        <h2 class="sub-title">جديد الصوتيات</h2>
      </div>
      <div class="latest-wrapper" role="tabpanel tabbable-panel">
        <!-- Nav tabs -->
        <div class="tabbable-line">
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#seremons" aria-controls="seremons" role="tab" data-toggle="tab">الخطب المنبرية</a>
            </li>
            <li role="presentation">
              <a href="#conferences" aria-controls="tab" role="tab" data-toggle="tab">المحاضرات و الكلمات</a>
            </li>
            <li role="presentation">
              <a href="#explanations" aria-controls="tab" role="tab" data-toggle="tab">الدروس العلمية</a>
            </li>
            <li role="presentation">
              <a href="#fatawas" aria-controls="tab" role="tab" data-toggle="tab">الفتاوى</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="seremons">
              <?php print views_embed_view('front_latest_content', 'seremons') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="conferences">
              <?php print views_embed_view('front_latest_content', 'conferences') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="explanations">
              <?php print views_embed_view('front_latest_content', 'explanations') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="fatawas">
              <?php print views_embed_view('front_latest_content', 'fatawas') ?>
            </div>
          </div>
        </div>
      </div>

      <div class="sub-title-wrapper">
        <h2 class="sub-title">جديد المكتبة المقروءة</h2>
      </div>
      <div class="latest-wrapper" role="tabpanel tabbable-panel">
        <!-- Nav tabs -->
        <div class="tabbable-line">
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#articles" aria-controls="tab" role="tab" data-toggle="tab">المقالات</a>
            </li>
            <li role="presentation">
              <a href="#fawaed" aria-controls="tab" role="tab" data-toggle="tab"> الفوائد المنتقاة</a>
            </li>
            <li role="presentation">
              <a href="#tafrigh" aria-controls="tab" role="tab" data-toggle="tab">التفريغات</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="articles">
              <?php print views_embed_view('front_latest_content', 'articles') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="fawaed">
              <?php print views_embed_view('front_latest_content', 'fawaed') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="tafrigh">
              <?php print views_embed_view('front_latest_content', 'tafrigh') ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-4 side-bar-first">
      <div class="block-wrapper main-radio-player-wrapper">
        <div class="sub-title-wrapper">
          <h2 class="sub-title">يبث الآن في الإذاعة</h2>
        </div>
        <div class="main-radio-player">
          <span id="current_playing"></span>
          <span class="align-center">
            <a id="radio-stream-url" href="#" class="play">
              <i class="fa fa-play"></i>
            </a>
            <span id="listners-count"></span><i class="fa fa-headphones" aria-hidden="true"></i>
          </span>
        </div>
      </div>

      <div class="block-wrapper">
        <div class="sub-title-wrapper">
          <h2 class="sub-title">التغريدات</h2>
        </div>
        <div class="tweet-feed">
          <a class="twitter-timeline" href="https://twitter.com/MiraathNet">Tweets by MiraathNet</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
      </div>

      <div class="block-wrapper">
        <div class="sub-title-wrapper">
          <h2 class="sub-title">مواقع ذات صلة</h2>
        </div>
        <a href="http://www.miraathpubs.net" class="custom-button" style="background-color: #0E8C1E;" title="ميراث الأنبياء (English)">
          <img src="<?php print $theme_image_path?>english-site-icon.png" alt="" class="custom-button-icon" style="height:50px !important;">
          <span class="custom-button-wrap">
          <span class="custom-button-title">ميراث الأنبياء (English)</span>
          <span class="custom-button-tagline">نرحب بكم في النسخة الانجليزية</span>
          </span>
          <em></em>
        </a>
        <a href="http://www.miraath.fr/" class="custom-button" style="background-color: #28919d;" title="ميراث الأنبياء (Français)">
          <img src="<?php print $theme_image_path?>french-site-icon.png" alt="" class="custom-button-icon" style="height:50px !important;">
          <span class="custom-button-wrap">
          <span class="custom-button-title">ميراث الأنبياء (Français)</span>
          <span class="custom-button-tagline">نرحب بكم في النسخة الفرنسية</span>
          </span>
          <em></em>
        </a>
          <a href="http://aicha.miraath.net" class="custom-button" style="background-color: #82001A;" title="موقع أم المؤمنين">
          <img src="<?php print $theme_image_path?>aicha-site-icon.png" alt="" class="custom-button-icon" style="height:50px !important;">
          <span class="custom-button-wrap">
          <span class="custom-button-title">موقع أم المؤمنين</span>
          <span class="custom-button-tagline">موقع خاص بأم المؤمنين عائشة رضي الله عنها</span>
          </span>
          <em></em>
        </a>
        <a href="" class="custom-button" style="background-color: #32000A;" title="موقع المكتبة">
          <img src="<?php print $theme_image_path?>maktaba-site-icon.png" alt="" class="custom-button-icon" style="height:50px !important;">
          <span class="custom-button-wrap">
          <span class="custom-button-title">موقع المكتبة</span>
          <span class="custom-button-tagline">نرحب بكم في موقع المكتبة الالكتروني</span>
          </span>
          <em></em>
        </a>
        <a href="http://miraath.net/sounds.php?cat=650" class="custom-button" style="background-color: #c5cace;" title="مجالس ميراث الأنبياء التأصيلية">
          <img src="<?php print $theme_image_path?>majaliss-site-icon.png" alt="" class="custom-button-icon" style="height:50px !important;">
          <span class="custom-button-wrap">
          <span class="custom-button-title">مجالس ميراث الأنبياء التأصيلية</span>
          <span class="custom-button-tagline">نرحب بكم في مجالس ميراث الأنبياء التأصيلية</span>
          </span>
          <em></em>
        </a>
        <a href="http://www.miraath.de" class="custom-button" style="background-color: #B48001;" title="موقع ميراث الألماني">
          <img src="<?php print $theme_image_path?>german-site-icon.png" alt="" class="custom-button-icon" style="height:50px !important;">
          <span class="custom-button-wrap">
          <span class="custom-button-title">موقع ميراث الألماني</span>
          <span class="custom-button-tagline">نرحب بكم في موقع ميراث الألماني</span>
          </span>
          <em></em>
        </a>
      </div>
    </div>
  </div>
</div>
