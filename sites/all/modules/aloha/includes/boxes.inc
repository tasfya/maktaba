<?php
/**
 * @file
 * Boxes module integration.
 */

/**
 * Implements hook_aloha_type_info() on behalf of boxes.module.
 */
function boxes_aloha_type_info() {
  return array(
    'boxes' => array(
      'save callback' => 'aloha_save_boxes',
      'access callback' => 'aloha_access_boxes',
    ),
  );
}

/**
 * Implements hook_aloha_form_alter() on behalf of boxes.module.
 */
function boxes_aloha_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'boxes_settings') {
    $form['boxes_edit_location']['#options']['aloha'] = t('Aloha Editor');
  }
}

/**
 * Implements hook_preprocess_boxes_box().
 */
function aloha_preprocess_boxes_box(&$vars, $hook) {
  // Check access.
  if (variable_get('boxes_edit_location', '') == 'aloha' && aloha_access_boxes() && _aloha_use_fee()) {
    if (_aloha_include_files()) {
      unset($vars['block']['controls']);
      $key = "boxes-{$vars['block']['delta']}";
      $region_data = array(
        'type' => 'boxes',
        'delta' => $vars['block']['delta'],
      );
      _aloha_add_target_to_region($vars['block']['content'], $key);
      _aloha_add_region_to_js($key, $region_data);
    }
  }
}

/**
 * Save callback for boxes.module.
 */
function aloha_save_boxes() {
  if (isset($_POST['html'])) {
    $box = boxes_box_load($_POST['delta']);
    $box->options['body']['value'] = $_POST['html'];
    $box->save();
    return drupal_json_output(array(
      'title' => 'Box',
      'type' => 'boxes',
      'status' => 'saved',
      'delta' => $_POST['delta'],
    ));
  }
  return;
}

/**
 * Access callback for boxes.module.
 */
function aloha_access_boxes() {
  return user_access('edit boxes') && user_access('use aloha');
}
