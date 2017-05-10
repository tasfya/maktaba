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
              <a href="#tafrigh" aria-controls="tab" role="tab" data-toggle="tab">التفريغات</a>
            </li>
            <li role="presentation">
              <a href="#fawaed" aria-controls="tab" role="tab" data-toggle="tab"> الفوائد المنتقاة</a>
            </li>
            <li role="presentation">
              <a href="#articles" aria-controls="tab" role="tab" data-toggle="tab">المقالات</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="articles">
              <?php print views_embed_view('front_latest_content', 'tafrigh') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="fawaed">
              <?php print views_embed_view('front_latest_content', 'fawaed') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="tafrigh">
              <?php print views_embed_view('front_latest_content', 'articles') ?>
            </div>
          </div>
        </div>

        <div class="latest-videos">
          <div class="sub-title-wrapper">
            <h2 class="sub-title">جديد المرئيات</h2>
          </div>
          <?php print views_embed_view('latest_videos'); ?>

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

        <div class="sub-title-wrapper">
          <h2 class="sub-title">البطاقات</h2>
        </div>
        <?php print views_embed_view('bitakate'); ?>

      <div class="block-wrapper">
        <div class="sub-title-wrapper">
          <h2 class="sub-title">البطاقات</h2>
        </div>
        <?php print views_embed_view('latest_videos'); ?>
      </div>

      <div class="block-wrapper">
        <div class="sub-title-wrapper">
          <h2 class="sub-title">التغريدات</h2>
        </div>
        <div class="tweet-feed hidden-xs">
          <a class="twitter-timeline" href="https://twitter.com/MiraathNet">Tweets by MiraathNet</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
      </div>

    </div>
  </div>
</div>
