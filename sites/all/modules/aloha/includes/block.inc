<?php
/**
 * @file
 * Block module integration.
 */

/**
 * Implements hook_aloha_type_info() on behalf of block.module.
 */
function block_aloha_type_info() {
  return array(
    'block' => array(
      'save callback' => 'aloha_save_block',
      'access callback' => 'aloha_access_block',
    ),
  );
}

/**
 * Implements hook_aloha_form_alter() on behalf of block.module.
 */
function block_aloha_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'aloha_admin') {
    $form['aloha_blocks'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable front end editing for blocks.'),
      '#description' => t('Enables front end editing globally for all blocks.'),
      '#default_value' => variable_get('aloha_blocks', TRUE),
    );
  }
}

/**
 * Implements hook_block_view_alter().
 */
function aloha_block_view_alter(&$data, $block) {
  // Check access.
  if ($block->module == 'block' && aloha_access_block() && _aloha_use_fee()) {
    if (_aloha_include_files()) {
      $key = "{$block->module}-{$block->delta}";
      $region_data = array(
        'type' => 'block',
        'delta' => $block->delta,
      );
      _aloha_add_target_to_region($data['content'], $key);
      _aloha_add_region_to_js($key, $region_data);
    }
  }
}

/**
 * Save callback for blocks.module.
 */
function aloha_save_block() {
  if (isset($_POST['html'])) {
    db_update('block_custom')
      ->fields(array(
        'body' => $_POST['html'],
      ))
      ->condition('bid', $_POST['delta'])
      ->execute();
    return drupal_json_output(array(
      'title' => 'Block',
      'type' => 'block',
      'status' => 'saved',
      'delta' => $_POST['delta'],
    ));
  }
  return;
}

/**
 * Access callback for blocks.module.
 */
function aloha_access_block() {
  return user_access('administer blocks') && user_access('use aloha') && variable_get('aloha_blocks', TRUE);
}
