
<?php if(!empty($output)): ?>
<div class="audiolinks">
  <a class="playlist playlink" href="<?php print $output ?>"><?php print t("play"); ?></a>
  <a class="downloadlink" href="<?php print $output ?>"><?php print t("download"); ?></a>
  <div class="clear clearfix"></div>
</div>
<?php endif; ?>