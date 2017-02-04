<?php

/**
 * @file
 * Contains the views ticker style plugin.
 */

/**
 * Style plugin to render each item in an ordered or unordered list.
 *
 * @ingroup views_style_plugins
 */
class views_ticker_style_plugin extends views_plugin_style {
  function option_definition() {
	$options = parent::option_definition();
	
	$options['scroller_type'] = array('default' => 'horizontal');

	$options['liScroll_Options']['default']=array (
	'liScroll_speed' => '0.07',
	'liScroll_direction' => 'left',
	'liScroll_mouseover' => 0,
	'liScroll_delay' => 0,
	'liScroll_bounce' => 0,
	);

	$options['vTicker_Options']['default']=array (
	'vTicker_mouseover' => 0,
	'vTicker_speed' => 500,
	'vTicker_pause' => 1000,
	'vTicker_items' => 5,
	'vTicker_direction' => 'up',
	);
	
	return $options;
  }

  function options_form(&$form, &$form_state) {
	parent::options_form($form, $form_state);

	$form['scroller_type'] = array(
		'#type' => 'select',
		'#title' => t('Scroller type'),
		'#description' => t(''),
		'#default_value' => $this->options['scroller_type'],
		'#options' => array(
		'horizontal' => t('Horizontal'), #liScroll
		'vertical' => t('Vertical'), #vTicker
		'fade' => t('Fade'),
		'bbc' => t('BBC'),
	  ),
	);
	
//####### horizontal Options (liScroll) ########
	// horizontal
	
	$form['liScroll_Options'] = array(
		'#type' => 'fieldset',
		'#title' => t('Horizontal scroller Options'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
	);
	
	$form['liScroll_Options']['liScroll_speed'] = array(
		'#type' => 'select',
		'#title' => t('Horizontal scroller speed'),
		'#description' => t(''),
		'#default_value' => $this->options['liScroll_Options']['liScroll_speed'],
		'#options' => array(
		'0.02' => t('Very slow'),
		'0.04' => t('Slow'),
		'0.07' => t('Normal'),
		'0.10' => t('Fast'),
		'0.15' => t('Very fast'),
	  ),
	);
	
	$form['liScroll_Options']['liScroll_direction'] = array(
		'#type' => 'select',
		'#title' => t('Horizontal scroller direction'),
		'#description' => t(''),
		'#default_value' => $this->options['liScroll_Options']['liScroll_direction'],
		'#options' => array(
		'left' => t('Left'),
		'right' => t('Right'),
	  ),
	);
	
	$form['liScroll_Options']['liScroll_mouseover'] = array(
		'#type' => 'checkbox',
		'#title' => t('Horizontal scroller mouseover'),
		'#description' => t(''),
		'#default_value' => $this->options['liScroll_Options']['liScroll_mouseover'],
	);
	
//####### vertical Options (vTicker) ########
	// vertical

	$form['vTicker_Options'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Vertical scroller Options'),
	  '#collapsible' => TRUE,
	  '#collapsed' => TRUE,
	);

	$form['vTicker_Options']['vTicker_items'] = array(
	  '#type' => 'textfield',
	  '#title' => t('Number of items'),
	  '#description' => t('Number of items to display at a time'),
	  '#default_value' => $this->options['vTicker_Options']['vTicker_items'],
	  '#element_validate' => array('vTicker_numeric_element_validate'),
	);

	$form['vTicker_Options']['vTicker_mouseover'] = array(
	  '#type' => 'checkbox',
	  '#title' => t('Pause on mouseover'),
	  '#description' => t(''),
	  '#default_value' => $this->options['vTicker_Options']['vTicker_mouseover'],
	);
	
	$form['vTicker_Options']['vTicker_pause'] = array(
	  '#type' => 'select',
	  '#title' => t('Scroller pause'),
	  '#description' => t(''),
	  '#default_value' => $this->options['vTicker_Options']['vTicker_pause'],
	  '#options' => array(
		500 => t('500 millisecond'),
		1000 => t('One second'),
		2000 => t('Two seconds'),
		3000 => t('Three seconds'),
		4000 => t('Four seconds'),
		5000 => t('Five seconds'),
	  ),
	);
	
	$form['vTicker_Options']['vTicker_speed'] = array(
	  '#type' => 'select',
	  '#title' => t('Scroller speed'),
	  '#description' => t(''),
	  '#default_value' => $this->options['vTicker_Options']['vTicker_speed'],
	  '#options' => array(
		1000 => t('Very slow'),
		700 => t('Slow'),
		500 => t('Normal'),
		300 => t('Fast'),
		150 => t('Very fast'),
	  ),
	);

	$form['vTicker_Options']['vTicker_direction'] = array(
		'#type' => 'select',
		'#title' => t('Scroller direction'),
		'#description' => t(''),
		'#default_value' => $this->options['vTicker_Options']['vTicker_direction'],
		'#options' => array(
		  'up' => t('Up'),
		  'down' => t('Down'),
		),
	);
  }

  function validate() 
  {
	$errors = parent::validate();
	if ($this->display->handler->use_pager()) 
	{
	  $errors[] = t('The ticker style cannot be used with a pager. Disable the "Use pager" option for this display.');
	}
	return $errors;
  }

}

function vTicker_numeric_element_validate($element, &$form_state) 
{
   if (!is_numeric($element['#value']) || $element['#value']<=0 ) 
   {
	 form_error($element, t('Enter a positive number'));
   }
}
