
<?php if(!empty($output)): ?>
<div class="audiolinks">
  <a class="playlist playlink" href="<?php print $output ?>"><?php print t("play"); ?></a>
  <a class="downloadlink" href="<?php print $output ?>"><?php print t("download"); ?></a>
  <div class="clear clearfix"></div>
  <?php // print sharethis_get_button_HTML(sharethis_get_options_array(), "http://www.unikar.ma", "this is a simple example"); ?>
</div>
<?php endif; ?>