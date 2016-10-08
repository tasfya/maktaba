<?php
/**
 * @file
 * Provides the Aloha Editors administrative interface.
 */

/**
 *  Aloha Editor Administrative settings form
 */
function aloha_admin() {
  $form = array();
  $options = array();
  $dir = libraries_get_path('aloha') . '/aloha/plugins';
  $categories = array('common', 'extra');
  if ($dir) {
    foreach ($categories as $category) {
      foreach (array_diff(scandir($dir . '/' . $category), array('.', '..')) as $folder) {
        if (is_dir($dir . '/' . $category . '/' . $folder)) {
          $options[$category . '/' . $folder] = drupal_ucfirst($folder);
        }
      }
    }
  }

  $form['aloha_plugins'] = array(
    '#type' => 'fieldset',
    '#title' => t('Plugins'),
    '#description' => t('Select plugins that you want to use with Aloha Editor.'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#tree' => TRUE,
    '#theme' => 'aloha_plugins_settings',
  );

  $plugins_status = variable_get('aloha_plugins', array());

  $form['aloha_plugins']['list'] = array(
    '#type' => 'checkboxes',
    '#options' => $options,
    '#default_value' => !empty($plugins_status) ? $plugins_status['list'] : array(),
  );

  if (!file_exists(libraries_get_path('aloha') . '/aloha/lib/aloha.js')) {
    drupal_set_message(check_plain(t('You need to download the !aloha and extract the entire contents of the archive into the %path folder of your server.', array('!aloha' => l(t('Aloha Editor'), 'http://www.aloha-editor.org/'), '%path' => 'sites/all/libraries/aloha'))), 'error', FALSE);
  }

  $is_active = FALSE;

  foreach ($options as $key => $value) {
    if (!empty($plugins_status)) {
      if (array_search($key, $plugins_status['list'])) {
        $is_active = TRUE;
      }
    }
  }

  if (empty($plugins_status) || !$is_active) {
    drupal_set_message(t('Activate some plugins to be able to actually use the Aloha Editor.'), 'warning');
  }

  $form = system_settings_form($form);
  $form['#submit'][] = 'aloha_admin_submit';

  return $form;
}

/**
 * Layout for the plugins list.
 *
 * @param <type> $variables
 *   Variables for theming
 */
function theme_aloha_plugins_settings($variables) {
  $form = $variables['form'];
  $plugins = array();

  // Flatten forms array.
  foreach (element_children($form) as $name) {
    foreach (element_children($form[$name]) as $plugin) {
      $plugins[] = drupal_render($form[$name][$plugin]);
    }
  }

  // Split checkboxes into rows with 3 columns.
  $total = count($plugins);
  $rows = array();
  for ($i = 0; $i < $total; $i++) {
    $row = array();
    $row[] = array('data' => $plugins[$i]);
    if (isset($plugins[++$i])) {
      $row[] = array('data' => $plugins[$i]);
    }
    if (isset($plugins[++$i])) {
      $row[] = array('data' => $plugins[$i]);
    }
    $rows[] = $row;
  }

  $output = theme('table', array('rows' => $rows, 'attributes' => array('width' => '100%')));

  return $output;
}

/**
 * Clear JS cache only on Save submit
 *
 * @param <type> $form
 *   Form submitted
 * @param <type> $form_state
 *   Form state submitted
 */
function aloha_admin_submit($form, &$form_state) {

  // Check if submit button was pressed and rebuild the functionality.
  // We must use "clicked_button" becaus values['op'] was unset by
  // system_settings_form_submit.
  $op = isset($form_state['clicked_button']['#value']) ? $form_state['clicked_button']['#value'] : '';
  if ($op == t('Save configuration')) {
    drupal_clear_js_cache();
  }
}
