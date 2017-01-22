<?php
/**
 * @file
 * The primary PHP file for this theme.
 */
function mir_preprocess_html(&$vars) {
	drupal_add_css('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array('type' => 'external'));
}

function mir_preprocess_page(&$vars){
	$vars['latest_conferences'] =
		views_embed_view('front_latest_content', 'latest_conferences');
	$vars['recent_content_ticker'] =
		views_embed_view('recent_content_ticker');

	if(drupal_is_front_page()){
		mir_preprocess_front_page($vars);
	}
}
function mir_preprocess_front_page(&$vars){
	$vars['front_carousel'] =
		views_embed_view('front_carousel');
}
