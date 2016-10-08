<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 * 
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */

function mufti_facetapi_link_inactive($variables) {
  // Builds accessible markup.
  // @see http://drupal.org/node/1316580
  $accessible_vars = array(
    'text' => $variables['text'], 
    'active' => FALSE,
  );
  $accessible_markup = theme('facetapi_accessible_markup', $accessible_vars);

  // Sanitizes the link text if necessary.
  $sanitize = empty($variables['options']['html']);
  
  $variables['text'] = ($sanitize) ? check_plain($variables['text']) : $variables['text'];
  
  //this line is added to prevent from showing 0 
  if($variables['text']=='0'){
    return;
  }
  // Adds count to link if one was passed.
 
 /*Custom count 
  if (isset($variables['count'])) {
    $variables['text'] .= ' ' . theme('facetapi_count', $variables);
  }
  */
if (isset($variables['count'])) {
  $variables['text'] = '<span class="facet-link">'.$variables['text'].'</span>';
  $variables['text'] .= '<span class="facet-count">'.$variables['count'].'</span>';
}
  // Resets link text, sets to options to HTML since we already sanitized the
  // link text and are providing additional markup for accessibility.
  $variables['text'] .= $accessible_markup;
  $variables['options']['html'] = TRUE;
  return theme_link($variables);
}


function mufti_preprocess_page(&$vars) {
  $vars['breadcrumb'] = '';
}