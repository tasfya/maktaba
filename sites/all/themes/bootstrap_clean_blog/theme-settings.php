<?php
/**
 * @file
 * Theme settings.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function bootstrap_clean_blog_form_system_theme_settings_alter(&$form, $form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  $form['instant_vertical_tabs'] = array(
    '#type' => 'vertical_tabs',
    '#prefix' => '<h2><small>' . t('Bootstrap Clean Blog settings') . '</small></h2>',
  );

  $form['header'] = array(
    '#type' => 'fieldset',
    '#title' => t('Site header'),
    '#group' => 'instant_vertical_tabs',
  );

  $article_fields = (array) field_info_instances('node', 'article');

  if (!empty($article_fields)) {
    foreach ($article_fields as $key => $field) {
      if ($field['widget']['type'] !== 'image_image') {
        unset($article_fields[$key]);
      }
      else {
        $article_fields[$key] = $field['field_name'];
      }
    }
  }

  $fields = (array) array_merge(array('none' => t('-- Select --')), $article_fields);

  $form['header']['header_image'] = array(
    '#type' => 'select',
    '#title' => t('Header image'),
    '#options' => $fields,
    '#description' => t('Big image for nodes, display in header. The field must be created for all types of material.'),
    '#default_value' => theme_get_setting('header_image'),
  );

  $form['social_buttons'] = array(
    '#type' => 'fieldset',
    '#title' => t('Social buttons'),
    '#group' => 'instant_vertical_tabs',
  );

  $form['social_buttons']['social_facebook'] = array(
    '#type' => 'textfield',
    '#title' => t('Facebook'),
    '#default_value' => theme_get_setting('social_facebook'),
  );

  $form['social_buttons']['social_twitter'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter'),
    '#default_value' => theme_get_setting('social_twitter'),
  );

  $form['social_buttons']['social_github'] = array(
    '#type' => 'textfield',
    '#title' => t('Github'),
    '#default_value' => theme_get_setting('social_github'),
  );
}
