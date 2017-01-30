<div class="container">
	<div class="carousel hidden-xs">
		<?php print $front_carousel; ?>
	</div>
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active">
				<a href="#seremons" aria-controls="seremons" role="tab" data-toggle="tab">آخر الخطب</a>
			</li>
			<li role="presentation">
				<a href="#tab" aria-controls="tab" role="tab" data-toggle="tab">آخر المحاضرات</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="seremons">
				<?php print views_embed_view('front_latest_content', 'seremons') ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="tab">
				<?php print views_embed_view('front_latest_content', 'conferences') ?>
			</div>
		</div>
	</div>
	<?php print $latest_conferences; ?>
</div>
<div class="container" style="margin: 0 auto">
	<div class="col-sm-6" style="max-width:500px;">
		<iframe src="http://tunein.com/embed/player/s137338/" style="width:100%;height:100px;" scrolling="no" frameborder="no"></iframe>
	</div>
	<div class="col-sm-6" style="max-width:500px;">
		<a class="twitter-timeline" href="https://twitter.com/MiraathNet">Tweets by MiraathNet</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
</div>
