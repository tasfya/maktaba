<?php
/**
 * @file
 * Process and preprocess for theme.
 */

/**
 * Implements hook_preprocess_html().
 */
function bootstrap_clean_blog_preprocess_html(&$variables) {
  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1',
    ),
  );

  drupal_add_html_head($viewport, 'viewport');

  // Css styles.
  drupal_add_css('http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', 'external');
  drupal_add_css('http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic', 'external');
  drupal_add_css('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800', 'external');

  // JS files.
  $html5shiv = array(
    '#tag' => 'script',
    '#attributes' => array(
      'src' => 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
    ),
    '#browsers' => array('IE' => 'lt IE 9', '!IE' => FALSE),
    '#weight' => 999,
  );
  drupal_add_html_head($html5shiv, 'html5shiv');

  $respond_js = array(
    '#tag' => 'script',
    '#attributes' => array(
      'type' => 'text/javascript',
      'src' => 'https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js',
    ),
    '#browsers' => array('IE' => 'lt IE 9', '!IE' => FALSE),
    '#weight' => 998,
  );
  drupal_add_html_head($respond_js, 'respond.js');
}

/**
 * Implements hook_blog_preprocess_page().
 */
function bootstrap_clean_blog_preprocess_page(&$variables) {
  $header_image = url(drupal_get_path('theme', 'bootstrap_clean_blog') . '/assets/img/home-bg.jpg');

  if (isset($variables['node'])) {
    $node = $variables['node'];

    // Full submitted for node data in page.tpl.php.
    $variables['submitted'] = t('Posted by !username on !datetime', array(
      '!username' => theme('username', array('account' => user_load($node->uid))),
      '!datetime' => format_date($node->created),
    ));

    $variables['display_submitted'] = (bool) variable_get('node_submitted_' . $node->type, TRUE);

    $field_header = theme_get_setting('header_image');

    if (isset($node->{$field_header}[LANGUAGE_NONE][0]['uri'])) {
      $header_image = $node->{$field_header}[LANGUAGE_NONE][0]['uri'];
      $header_image = file_create_url($header_image);
    }
  }

  $variables['header_image'] = $header_image;
}

/**
 * Implements hook_html_head_alter().
 */
function bootstrap_clean_blog_html_head_alter(&$head_elements) {
  if (isset($head_elements['system_meta_content_type'])) {
    $head_elements['system_meta_content_type']['#attributes'] = array(
      'charset' => 'utf-8',
    );
  }
}

/**
 * Implements hook_menu_tree__MENUNAME().
 */
function bootstrap_clean_blog_menu_tree__primary(&$variables) {
  return '<ul class="nav navbar-nav navbar-right">' . $variables['tree'] . '</ul>';
}
