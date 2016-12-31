<?php

/**
 * @file
 * Views integration for ticker module.
 */

/**
 * Implements hook_views_plugin().
 */
function views_ticker_views_plugins()
{
	$plugins['style']['views_ticker'] = array('title' => t('Views Ticker'), 'help' => t('Display links to items while scrolling through them.'), 'handler' => 'views_ticker_style_plugin', 'path' => drupal_get_path('module', 'views_ticker') . '/includes', 'theme' => 'views_ticker_view', 'theme path' => drupal_get_path('module', 'views_ticker') . '/includes', 'uses row plugin' => true, 'uses options' => true, 'uses grouping' => false, 'type' => 'normal', );
	return $plugins;
}

function template_preprocess_views_ticker_view(&$variables)
{
	$path = drupal_get_path('module', 'views_ticker');
	drupal_add_css($path . '/views.ticker.scroller.css');
	
	$view = $variables['view'];
	$options = $view->style_plugin->options;
	$variables['viewname'] = $view->name;
//	$variables['field'] = $view->field['title']->field_alias;
	$variables['scroller_type'] = $options['scroller_type'];

	
	if ($options['scroller_type'] == 'horizontal')
	{
		$hOpts=$options['liScroll_Options'];
		
		$liScrollOpts = ($hOpts['liScroll_mouseover'] ? 'pauseOnMouse: true' : 'pauseOnMouse: false').
						', travelocity:'.$hOpts['liScroll_speed'].
						', direction: "' . $hOpts['liScroll_direction'] . '"';
		
	}
	elseif ($options['scroller_type'] == 'vertical')
	{
		$vTickerOpts = $options['vTicker_Options']['vTicker_mouseover'] ? 'mousePause: true' : 'mousePause: false';
		$vTickerOpts .= ' , speed:' . $options['vTicker_Options']['vTicker_speed'];
		$vTickerOpts .= ' , pause:' . $options['vTicker_Options']['vTicker_pause'];
		$vTickerOpts .= ' , showItems:' . $options['vTicker_Options']['vTicker_items'];
		$vTickerOpts .= ' , direction: "' . $options['vTicker_Options']['vTicker_direction'] . '"';
	}
	else
	{
		$variables['align'] = 'fade-container';
	}
	
	if ($options['scroller_type'] == 'fade')
	{
		drupal_add_js($path . '/js/views.ticker.fade.js');
		drupal_add_js('(function($) { Drupal.behaviors.views_ticker_fade = { attach: function (context) { $("#views-ticker-fade-' . $view->name . '").newsTickerFade(); } };})(jQuery);', 'inline');
	}
	elseif ($options['scroller_type'] == 'bbc')
	{
		drupal_add_js($path . '/js/views.ticker.bbc.js');
		drupal_add_js('(function($) { Drupal.behaviors.views_ticker_bbc = { attach: function (context) { var options = { newsList: "#views-ticker-bbc-' . $view->name . '", startDelay: 10, placeHolder1: " |"}; $().newsTicker(options);}};})(jQuery);', 'inline');
	}
	elseif ($options['scroller_type'] == 'vertical')
	{
		drupal_add_js($path . '/js/jquery.vticker-min.js');
		drupal_add_js('(function($) { Drupal.behaviors.views_ticker_vTicker = { attach: function (context) { $("#views-ticker-vTicker-' . $view->name . '").vTicker({' . $vTickerOpts . '}); } };})(jQuery);', 'inline');
	}
	elseif ($options['scroller_type'] == 'horizontal')
	{
		drupal_add_js($path . '/js/jquery.liscroller.js');
		drupal_add_js('(function($) { Drupal.behaviors.views_ticker_liScroll = { attach: function (context) { $("#views-ticker-liScroll-' . $view->name . '").liScroll({'.$liScrollOpts.'}); } };})(jQuery);', 'inline');
	}
}
